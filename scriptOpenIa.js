#!/usr/bin/env node

import fs from 'fs/promises';
import path from 'path';
import dotenv from 'dotenv';
import yargs from 'yargs';
import { hideBin } from 'yargs/helpers';
import { Configuration, OpenAIApi } from 'openai';
import simpleGit from 'simple-git';

// Carga .env
dotenv.config();
const OPENAI_KEY = process.env.OPENAI_API_KEY;
if (!OPENAI_KEY) {
  console.error('❌ Define OPENAI_API_KEY en un archivo .env');
  process.exit(1);
}

// Configura cliente OpenAI y Git
const openai = new OpenAIApi(new Configuration({ apiKey: OPENAI_KEY }));
const git = simpleGit();

// Parámetros CLI
const argv = yargs(hideBin(process.argv))
  .option('src',   { alias: 's', type: 'string', default: 'app',       describe: 'Carpeta origen' })
  .option('batch', { alias: 'b', type: 'number', default: 5,           describe: 'Archivos por lote' })
  .option('delay', { alias: 'd', type: 'number', default: 1000,        describe: 'Delay entre llamadas (ms)' })
  .option('dry',   { alias: 'n', type: 'boolean', default: false,      describe: 'Dry-run (no aplica patches)' })
  .argv;

async function listPhpFiles(dir) {
  const entries = await fs.readdir(dir, { withFileTypes: true });
  const files = await Promise.all(entries.map(async ent => {
    const full = path.join(dir, ent.name);
    if (ent.isDirectory()) return listPhpFiles(full);
    if (ent.isFile() && full.endsWith('.php')) return [full];
    return [];
  }));
  return files.flat();
}

// Construye el prompt parametrizable
function buildPrompt(code, relativePath) {
  return `
Eres un asistente experto en Laravel. Este es el archivo \`${relativePath}\` de un proyecto en Laravel 5.9:

\`\`\`php
${code}
\`\`\`

Transforma todo el código para que sea compatible con **Laravel 12** y **PHP 8.2**:
- Reemplaza helpers obsoletos (Request::all(), Validator::make(), etc.).
- Convierte validaciones inline a FormRequest.
- Actualiza factories y seeders al nuevo API.
- Adapta rutas, middleware y eventos a la sintaxis actual.
- Mantén la lógica original.

Devuélveme **únicamente** el **diff unificado** (`git diff -u`) con rutas relativas correctas.
`.trim();
}

async function migrateBatch(files) {
  for (const filePath of files) {
    const relPath = path.relative(process.cwd(), filePath);
    const code = await fs.readFile(filePath, 'utf8');
    const prompt = buildPrompt(code, relPath);

    // Llamada a OpenAI
    const resp = await openai.createChatCompletion({
      model: 'gpt-4o-mini',
      messages: [{ role: 'user', content: prompt }],
      temperature: 0,
    });
    const diff = resp.data.choices[0].message.content;

    console.log(`\n📄 Procesando: ${relPath}`);
    if (!diff.includes('diff --git')) {
      console.warn('⚠️  No se detectó un diff válido, se guarda en suggestions/');
      await fs.writeFile(`suggestions/${relPath.replace(/\//g,'_')}.patch.txt`, diff);
    } else if (!argv.dry) {
      try {
        await git.raw(['apply', '--whitespace=fix'], diff);
        console.log('✅ Patch aplicado');
      } catch (err) {
        console.error('❌ Error aplicando patch:', err.message);
        // Guarda el diff para revisión
        await fs.writeFile(`patches/${relPath.replace(/\//g,'_')}.patch`, diff);
      }
    }

    // Delay para respetar rate limits
    await new Promise(res => setTimeout(res, argv.delay));
  }
}

async function main() {
  const allFiles = await listPhpFiles(argv.src);
  console.log(`🗂️  Archivos detectados: ${allFiles.length}`);
  // Divide en lotes
  for (let i = 0; i < allFiles.length; i += argv.batch) {
    const batch = allFiles.slice(i, i + argv.batch);
    console.log(`\n🔄 Lote ${Math.floor(i/argv.batch)+1}: ${batch.length} archivos`);
    await migrateBatch(batch);
  }
  console.log('\n🎉 Proceso completado. Revisa “patches/” y “suggestions/”.');
}

main().catch(err => {
  console.error('Fatal:', err);
  process.exit(1);
});

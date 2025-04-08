<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket Pedido #{{ $pedido->id }}</title>
</head>
<body>
    <h1>Ticket Pedido #{{ $pedido->id }}</h1>
    <p><strong>Cliente:</strong> {{ $pedido->cliente->nombre }}</p>
    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y H:i') }}</p>
    <hr>
    <table width="100%" border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->pivot->cantidad }}</td>
                <td>${{ number_format($producto->pivot->precio_unitario, 2) }}</td>
                <td>${{ number_format($producto->pivot->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <p><strong>Total:</strong> ${{ number_format($pedido->total, 2) }}</p>
</body>
</html>

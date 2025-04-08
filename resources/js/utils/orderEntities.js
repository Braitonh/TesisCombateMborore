import Sortable from 'sortablejs';

export function orderEntities(tableId, wireMethod, livewireId) {
    const table = document.getElementById(tableId);
    if (!table || table.classList.contains('sortable-applied')) return;

    new Sortable(table, {
        animation: 150,
        ghostClass: 'bg-blue-100',
        onEnd: function (evt) {
            const orden = Array.from(evt.to.children).map(row => row.dataset.id);
            Livewire.find(livewireId).call(wireMethod, orden);
        },
    });

    table.classList.add('sortable-applied');
}


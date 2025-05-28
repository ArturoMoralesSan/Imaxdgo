<script>
export default {
    props: ['model', 'sortBy', 'sortDir', 'search'],

    data() {
        return {
            sortKey: this.sortBy || 'last_name',
            sortAsc: this.sortDir === 'asc',
        }
    },

    methods: {
        sortByColumn(column) {
            let newSortDir = 'asc';
            if (this.sortKey === column) {
                newSortDir = this.sortAsc ? 'desc' : 'asc';
            }
            // Crear query params para recargar la página
            const params = new URLSearchParams(window.location.search);
            params.set('sort_by', column);
            params.set('sort_dir', newSortDir);
            if (this.search) {
                params.set('search', this.search);
            } else {
                params.delete('search');
            }
            // Recargar con nuevo orden y búsqueda
            window.location.href = window.location.pathname + '?' + params.toString();
        },

        isSorted(column) {
            return this.sortKey === column;
        }
    }
}


</script>
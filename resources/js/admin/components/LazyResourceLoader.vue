<template>
  <div>
    <table class="table size-caption mx-auto mb-8 md:table--responsive">
      <thead>
        <tr class="table-resource__headings">
          <th v-for="(heading, index) in headings" :key="index">
            {{ heading }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, index) in visibleItems"
          :key="item.id || index"
          class="table-resource__row"
        >
          <slot name="row" :item="item" />
        </tr>
      </tbody>
    </table>

    <div class="text-center">
      <button
        v-if="canLoadMore"
        @click="loadMore"
        class="btn btn-sm btn-primary"
      >
        Mostrar más
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LazyResourceTable',
  props: {
    items: {
      type: Array,
      required: true,
    },
    chunkSize: {
      type: Number,
      default: 5,
    },
    maxItems: {
      type: Number,
      default: 30,
    },
    headings: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      limit: this.chunkSize,
    };
  },
  computed: {
    visibleItems() {
      return this.items.slice(0, this.limit);
    },
    canLoadMore() {
      return this.limit < Math.min(this.maxItems, this.items.length);
    },
  },
  methods: {
    loadMore() {
      this.limit += this.chunkSize;
    },
  },
};
</script>

<style scoped>
/* Puedes agregar estilos personalizados aquí */
</style>

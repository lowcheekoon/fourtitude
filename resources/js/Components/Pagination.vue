<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, key) in links">
        <div v-if="link.url === null" :key="key" class="mb-1 mr-1 px-4 py-3 text-gray-400 text-sm leading-4 border rounded" v-html="link.label" />
        <div v-else :key="`link-${key}`" class="link mb-1 mr-1 px-4 py-3 focus:text-indigo-500 text-sm leading-4 hover:bg-white border focus:border-indigo-500 rounded" :class="{ 'bg-white': link.active }" @click="pagination(link.url)" v-html="link.label" />
      </template>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
  components: {
    Link,
  },
  props: {
    links: Array,
  },
  methods: {
    pagination( url ) {
      this.$emit('go-pagination', url);
    }
  },
  /*
  props: {
    page: Number,
    total: Number,
    size: Number,
    url: String,
  },
  data() {
    return {
      links: [],
    }
  },
  beforeMount() {
    const reminder = this.total % this.size;
    const maxPage = parseInt( this.total / this.size ) + ( reminder ? 1 : 0 );
    if( maxPage > 1 ) {
      let loop = maxPage + 2;
      for(let i=0; i<=loop; i++) {
        let label = i;
        let query = i;
        if( i==0 ) {
          label = 'Previous';
          query = page == 1 ? '' : page - 1;
        } else if( i == loop ) {
          label = 'Next';
          query = page == maxPage ? '' : page + 1;
        }

        if( query != '' ) {
          let content = {
            active: false,
            label: label,
            url: this.url + "?page=" + query,
          }
          this.links[i] = content;
        } 
      }
    }
    console.log( this.links );
  },
  */
}
</script>
<style scoped>
.link {
  cursor: pointer;
}
</style>

<script>
import { Head, Link } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Pagination from '@/Components/Pagination.vue';
import axios from 'axios';

export default {
  components: {
    Head,
    Link,
    SecondaryButton,
    DangerButton,
    Pagination,
  },
  data() {
      return {
        loading: false,
        errored: false,
        size: 10,
        page: 1,
        total: 0,
        links: Array,
        datas: [],
        sizes:[10, 15, 20, 25],
      }
  },
  methods: {
    initialUrl() { return '/product/get-list?page=' + this.page + '&size=' + this.size },
    fetchData: async function( url ) {
      this.datas = [];
      this.links = [];
      this.loading = true;
      await axios.get( url )
                 .then(response => {
                    if( response.data.status == 'ok' ) {
                      const data = response.data.data;
                      this.datas = data.data;
                      this.links = data.links;
                    }
                  })
                 .catch(error => {
                    console.log( 'error' );
                    console.log(error)
                    this.errored = true
                  })
                  .finally(() => { this.loading = false });
    },
    changeSize() {
      this.page = 1;
      this.fetchData( this.initialUrl() );
    },
    goForm (actionValue, codeValue) {
      this.$emit('go-form', actionValue, codeValue);
    },
    async receiveGoPagination(url) {
      await this.fetchData( url );
    },
    async deleteRecord(codeValue) {
      this.loading = true;
      await axios.delete( '/product/' + codeValue )
                 .then( async response =>  {
                    if( response.data.status == 'ok') {
                      await this.fetchData( this.initialUrl() );
                    }
                  })
                 .catch(error => {
                    console.log( 'error' );
                    console.log(error)
                    this.errored = true
                  })
                  .finally(() => { this.loading = false });
    }
  },
  async created() { await this.fetchData( this.initialUrl() ); },
}

</script>

<template>
  <div>
    <Head title="Product List" />
    <h1 class="mb-8 text-3xl font-bold">
      <!--Link class="text-indigo-400 hover:text-indigo-600" href="/contacts">Product</Link-->
      <!--span class="text-indigo-400 font-medium">/</span-->List
      
    </h1>
    <div>
      <label class="mr-2">Page Size:</label>
      <select v-model="size" @change="changeSize()" class="bg-white rounded-md font-semibold text-xs text-gray-700 border border-gray-300">
        <option v-for="s in sizes" :value="s">{{ s }}</option>
      </select>
      <SecondaryButton @click="goForm('new','')" class="ml-2">New Product</SecondaryButton>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">ID</th>
          <th class="pb-4 pt-6 px-6">Code</th>
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">Category</th>
          <th class="pb-4 pt-6 px-6">Brand</th>
          <th class="pb-4 pt-6 px-6">Type</th>
          <th class="pb-4 pt-6 px-6">Description</th>
          <th class="pb-4 pt-6 px-6">Action</th>
        </tr>
        <tr v-if="! loading" v-for="data in datas" :key="data.id" class="hover:bg-gray-100 focus-within:bg-gray-100 h-16">
          
          <td class="border-t px-6">{{ data.id }}</td>
          <td class="border-t px-6">
            <div class="link flex items-center focus:text-indigo-500" @click="goForm('edit', data.code)">{{ data.code }}</div>
          </td>
          <td class="border-t px-6">{{ data.name }}</td>
          <td class="border-t px-6">{{ data.category }}</td>
          <td class="border-t px-6">{{ data.brand }}</td>
          <td class="border-t px-6">{{ data.type }}</td>
          <td class="border-t px-6">{{ data.description }}</td>
          <td class="border-t px-6">
            <DangerButton @click="deleteRecord(data.code)">Delete</DangerButton>
          </td>

        </tr>
        <tr v-if="! loading && datas.length == 0">
          <td class="px-6 py-4 border-t text-center" colspan="7">No Data found.</td>
        </tr>
        <tr v-if="loading">
          <td class="px-6 py-4 border-t text-center" colspan="7">Loading ...</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="links" @go-pagination="receiveGoPagination" />
  </div>
</template>

<style scoped>
.link {
  color: blue;
  cursor: pointer;
}
</style>
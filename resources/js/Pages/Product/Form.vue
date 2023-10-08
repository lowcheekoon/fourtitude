<template>
    <div>
      <Head title="Create Product" />
      <h1 class="mb-8 text-3xl font-bold">
        <!--Link class="text-indigo-400 hover:text-indigo-600" href="/contacts">Product</Link-->
        <!--span class="text-indigo-400 font-medium">/</span-->
        {{ action == 'edit' ? 'Edit' : 'Create' }}
      </h1>
      <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="submit">
          <div class="flex flex-wrap -mr-6 p-8">
            <!--div class="pb-8 pr-6 w-full lg:w-1/2" ></div-->

            <text-input-label v-model="form.code" :label="'Code'" :error-message="form.errors.code" :tag="'input'" :disabled="action == 'edit'" />
            <text-input-label v-model="form.name" :label="'Name'" :error-message="form.errors.name" :tag="'input'" :disabled="false" />
            <text-input-label v-model="form.category" :label="'Category'" :error-message="form.errors.category" :tag="'input'" :disabled="false" />
            <text-input-label v-model="form.brand" :label="'Brand'" :error-message="form.errors.brand" :tag="'input'" :disabled="false" />
            <text-input-label v-model="form.type" :label="'Type'" :error-message="form.errors.type" :tag="'input'" :disabled="false" />
            <text-input-label v-model="form.description" :label="'Description'" :error-message="form.errors.description" :tag="'textarea'" :disabled="false" />
            <div class="flex items-center gap-4">
              <SecondaryButton @click="goBack">Cancel</SecondaryButton>
              <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

              <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
              </Transition>
            </div>

          </div>

        </form>
      </div>
    </div>
  </template>
  
  <script>
  import { Head, Link } from '@inertiajs/vue3';
  import TextInputLabel from '@/Components/TextInputLabel.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import axios from 'axios';
  
  export default {
    components: {
      Head,
      Link,
      TextInputLabel,
      PrimaryButton,
      SecondaryButton,
    },
    props: {
      code: '',
      action: 'new',
    },
    data() {
      return {
        loading: false,
        form: this.$inertia.form({
          code: '',
          name: '',
          category: '',
          brand: '',
          type: '',
          description: '',
        }),
      }
    },
    methods: {
      fillData(data) {
        this.form = this.$inertia.form({
          code: data['code'] ?? '',
          name: data['name'] ?? '',
          category: data['category'] ?? '',
          brand: data['brand'] ?? '',
          type: data['type'] ?? '',
          description: data['description'] ?? '',
        });
      },
      fetchData: async function( codeValue ) {
        this.loading = true;
        this.errored = false;
        await axios.get('/product/' + codeValue)
                  .then(response => {
                      if( response.data.status == 'ok' ) {
                        this.fillData( response.data.data );
                      }
                    })
                  .catch(error => {
                      console.log( 'error' );
                      console.log(error)
                      this.errored = true
                    })
                  .finally(() => { this.loading = false });
      },
      submit() {
        this.action == 'edit' ? this.update() : this.store();
      },
      async store() {
        
        const params = {
          'code': this.form.code,
          'name': this.form.name,
          'category': this.form.category,
          'brand': this.form.brand,
          'type': this.form.type,
          'description': this.form.description
        };

        this.form.errors = null;
        this.loading = true;
        this.errored = false;
        await axios.post('/product/create', params)
                    .then(response => {
                      if( response.data.status == 'ok') {
                        this.goBack();
                      }
                    })
                    .catch(error => {
                      if( error.response.status == 422 ) {
                        this.form.errors = error.response.data.errors;
                      }
                      this.errored = true
                    })
                    .finally(() => { this.loading = false });
      },
      async update() {
        const params = {
          'name': this.form.name,
          'category': this.form.category,
          'brand': this.form.brand,
          'type': this.form.type,
          'description': this.form.description
        };

        this.form.errors = null;
        this.loading = true;
        this.errored = false;
        await axios.put('/product/' + this.form.code, params)
                    .then(response => {
                      if( response.data.status == 'ok') {
                        this.goBack();
                      }
                    })
                    .catch(error => {
                      if( error.response.status == 422 ) {
                        this.form.errors = error.response.data.errors;
                      }
                      this.errored = true;
                    })
                    .finally(() => { console.log( 'finally' ); this.loading = false });
      },
      goBack() {
        this.$emit('go-back');
      },
    },
    beforeMount() {
      if(this.action == 'edit') {
        this.fetchData( this.code );
      }
    }
  }
  </script>
  
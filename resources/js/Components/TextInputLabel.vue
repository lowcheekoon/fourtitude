<script setup>
import { computed } from 'vue'
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps(['modelValue', 'label', 'errorMessage', 'tag', 'disabled']);

const emit = defineEmits(['update:modelValue']);

const value = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emit('update:modelValue', value)
  }
});

</script>

<template>
    <div class="pb-8 pr-6 w-full">
      <div class="w-25 inline-block items-center justify-items-center">
        <InputLabel :for="label" :value="label" />
      </div>
      
      <div class="w-75 inline-block">
        <TextInput v-if="tag == 'input'"
              id="{{label}}"
              type="text"
              class="mt-1 block w-full"
              v-model="value"
              autofocus
              autocomplete="{{label}}"
              :disabled="disabled"
          />
        <TextareaInput v-if="tag == 'textarea'"
              id="{{label}}"
              type="text"
              class="mt-1 block w-full"
              v-model="value"
              autofocus
              autocomplete="{{label}}"
              :disabled="disabled"
          />
      </div>

      <div class="w-25 inline-block items-center justify-items-center"></div>
      <div class="w-75 inline-block">
        <InputError class="mt-2" :message="errorMessage ? errorMessage.toString() : ''" />
      </div>
      
    </div>

</template>

<style scoped>
.w-25{
  width: 25%;
}
.w-75 {
  width: 75%;
}
</style>

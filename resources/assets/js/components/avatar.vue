<template>
  <object :data="photo || '//'" type="image/png">
    <img src="/img/avatar.png" :alt="name" class="mw-100">

    <slot></slot>
  </object>
</template>

<script>
  import { default as mixins } from './mixins/user';

  export default {
    mixins: [ mixins ],
    props: {
      name: {
        type: String
      },
      id: {
        type: Number
      },
      photo: {
        type: String
      }
    }
  }
</script>

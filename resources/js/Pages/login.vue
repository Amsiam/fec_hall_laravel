<template>
  <div class="lg:w-1/2 mx-auto lg:my-10 p-10 border border-gray-400 rounded-xl">
    <form @submit.prevent="login">
      <div class="mb-6">
        <label
          for="email"
          class="
            block
            mb-2
            text-sm
            font-medium
            text-gray-900
            dark:text-gray-300
          "
        >
          Your email
        </label>
        <input
          type="email"
          id="email"
          v-model="form.email"
          class="
            bg-gray-50
            border border-gray-300
            text-gray-900 text-sm
            rounded-lg
            focus:ring-blue-500 focus:border-blue-500
            block
            w-full
            p-2.5
            dark:bg-gray-700
            dark:border-gray-600
            dark:placeholder-gray-400
            dark:text-white
            dark:focus:ring-blue-500
            dark:focus:border-blue-500
          "
          placeholder="name@gmail.com"
          required=""
        />
        <p
          v-if="form.errors.email"
          class="mt-2 text-sm text-red-600 dark:text-red-500"
        >
          <span class="font-medium">Oops!</span> {{ form.errors.email }}
        </p>
      </div>
      <div class="mb-6">
        <label
          for="password"
          class="
            block
            mb-2
            text-sm
            font-medium
            text-gray-900
            dark:text-gray-300
          "
        >
          Your password
        </label>
        <input
          type="password"
          id="password"
          v-model="form.password"
          class="
            bg-gray-50
            border border-gray-300
            text-gray-900 text-sm
            rounded-lg
            focus:ring-blue-500 focus:border-blue-500
            block
            w-full
            p-2.5
            dark:bg-gray-700
            dark:border-gray-600
            dark:placeholder-gray-400
            dark:text-white
            dark:focus:ring-blue-500
            dark:focus:border-blue-500
          "
          required=""
        />

        <p
          v-if="form.errors.password"
          class="mt-2 text-sm text-red-600 dark:text-red-500"
        >
          <span class="font-medium">Oops!</span> {{ form.errors.password }}
        </p>
      </div>
      <button
        type="submit"
        class="
          text-white
          bg-blue-700
          hover:bg-blue-800
          focus:ring-4 focus:outline-none focus:ring-blue-300
          font-medium
          rounded-lg
          text-sm
          w-full
          sm:w-auto
          px-5
          py-2.5
          text-center
          dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800
        "
        :disabled="form.processing"
      >
        Submit
      </button>
    </form>
  </div>
</template>

<script>
export default {
layout:null
}
</script>
<script setup>
import { useForm } from "@inertiajs/inertia-vue3";

let form = useForm({
  email: "",
  password: "",
});

let login = () => {
  form.post("/login");
};
</script>

<style>
</style>

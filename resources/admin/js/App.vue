<template>
  <div id="app">
    <transition props="fade-in">
      <router-view />
    </transition>
  </div>
</template>
<style>
html,
body {
  width: 100%;
  height: 100%;
}
</style>

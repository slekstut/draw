export default {
  // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
  ssr: false,

  // Target: https://go.nuxtjs.dev/config-target
  target: 'static',

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'draw_app',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    'bootstrap/dist/css/bootstrap.css',

  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    { src: '~/plugins/konva.js', ssr: false },
    { src: '~/plugins/vue-toasted.js', ssr: false },
    { src: '~/plugins/toasted.js' },
    { src: '~/plugins/vue-cookies', ssr: false },
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/bootstrap
    'bootstrap-vue/nuxt',
    '@nuxtjs/axios',
  ],
  axios: {
    // Base URL of your API
    baseURL: 'http://localhost:8000/api',
    // Enable credentials
    withCredentials: true,
    // Headers to send with every request
    headers: {
      common: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    },
    xsrfHeaderName: 'X-XSRF-TOKEN',
    withCredentials: true
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  },
}

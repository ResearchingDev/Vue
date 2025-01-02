<template>
  <div class="header-wrapper row m-0">
    <SearchBar />
    <Logo/>
    <div class="left-header col-xxl-5 col-xl-6 col-lg-5 col-md-4 col-sm-3 p-0">
    </div>
    <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
      <ul class="nav-menus">
        <li class="language-nav">
          <Language />
        </li>
        <Profile />
      </ul>
    </div>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  import Bookmark from '../bookmark.vue';
  import { Swiper, SwiperSlide } from "swiper/vue";
  import { Autoplay } from "swiper";
import "swiper/css";
  import Language from './language.vue';
  import Notifications from './notifications.vue';
  import Mode from './mode.vue';
  import Cart from './cart.vue';
  import Profile from './profile.vue';
  import Logo from './logo.vue';
  import SearchBar from './search.vue';
  export default {
    components: {
      Bookmark,
      Language,
      Notifications,
      Mode,
      Cart,
      Profile,
      Logo,
      Swiper,
      SwiperSlide,
      SearchBar,
    },
    data() {
      return {
        bookmark: false,
      };
    },
    computed: {
      ...mapState({
        menuItems: (state) => state.menu.searchData,
        megamenuItems: (state) => state.menu.megamenu,
        searchOpen: (state) => state.menu.searchOpen,
      }),
    },
    methods: {
      search_open() {
        this.$store.state.menu.searchOpen = true;
      },
      bookmark_open() {
        this.bookmark = !this.bookmark;
      },
    },
    setup() {
    return {
      modules: [Autoplay],
    };
  },
  };
</script>

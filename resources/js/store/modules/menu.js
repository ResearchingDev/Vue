import { menuItems } from '../../data/menu.js';
import BonusUI from '../../data/bonusui';

const state = {
    data: [],
    loading: false,
    megamenu: BonusUI.data,
    searchData: [],
    togglesidebar: true,
    activeoverlay: false,
    searchOpen: false,
  customizer: '',
    hideRightArrowRTL: false,
    hideLeftArrowRTL: true,
    hideRightArrow: true,
    hideLeftArrow: true,
    width: 0,
    height: 0,
    margin: 0,
    menuWidth: 0,
};

const mutations = {
  setUserData: (state) => {
    const user = localStorage.getItem('User');
    if (user) {
      try {
            const parsedUser = JSON.parse(user);
        // Set the data immediately after fetching from localStorage
        state.data = menuItems[parsedUser.role_code]?.data || menuItems.client.data;
            } catch (error) {
        console.error('Error parsing user data:', error);
        state.data = menuItems.client.data; // Fallback
            }
    } else {
      state.data = menuItems.super_admin.data; // Fallback if no user in localStorage
        }
    },

    // Update Sidebar state
    opensidebar: (state) => {
        state.togglesidebar = !state.togglesidebar;
        if (window.innerWidth < 991) {
            state.activeoverlay = true;
        } else {
            state.activeoverlay = false;
        }
    },

    // Resize sidebar on window resize
    resizetoggle: (state) => {
        if (window.innerWidth < 1007) {
            state.togglesidebar = false;
        } else {
            state.togglesidebar = true;
        }
    },

    // Filter data based on search term
    searchTerm: (state, term) => {
        let items = [];
        const searchval = term.toLowerCase();
    state.data.forEach(menuItems => {
            if (menuItems.title) {
        if (menuItems.title.toLowerCase().includes(searchval) && menuItems.type === 'link') {
                    items.push(menuItems);
                }
                if (menuItems.children) {
          menuItems.children.forEach(subItems => {
            if (subItems.title.toLowerCase().includes(searchval) && subItems.type === 'link') {
                            subItems.icon = menuItems.icon;
                            items.push(subItems);
                        }
                        if (subItems.children) {
              subItems.children.forEach(suSubItems => {
                if (suSubItems.title.toLowerCase().includes(searchval)) {
                                    suSubItems.icon = menuItems.icon;
                                    items.push(suSubItems);
                                }
                            });
                        }
                    });
                }
            }
        });
        state.searchData = items;
    },

    // Set active state for Bonus Navigation
    setBonusNavActive: (state, item) => {
        if (!item.active) {
      state.megamenu.forEach(a => {
                if (state.megamenu.includes(item)) a.active = false;
                if (a.children) {
          a.children.forEach(b => {
                        if (a.children.includes(item)) {
                            b.active = false;
                        }
                    });
                }
            });
        }
        item.active = !item.active;
    },

    // Set active state for Navigation
    setNavActive: (state, item) => {
        if (!item.active) {
      state.data.forEach(a => {
                    if (state.data.includes(item)) a.active = false;
                    if (a.children) {
          a.children.forEach(b => {
                            if (a.children.includes(item)) {
                                b.active = false;
                            }
                        });
                    }
                });
            }
        item.active = !item.active;
    },

    // Set active route for navigation
    setActiveRoute: (state, item) => {
    state.data.forEach(menuItem => {
            if (menuItem !== item) menuItem.active = false;
            if (menuItem.children && menuItem.children.includes(item)) {
                item.active = true;
                menuItem.active = true;
            }
            if (menuItem.children) {
        menuItem.children.forEach(submenuItems => {
          if (submenuItems.children && submenuItems.children.includes(item)) {
                        item.active = true;
                        menuItem.active = true;
                        submenuItems.active = true;
                    }
                });
            }
        });
  }
};

const actions = {
    // Load user data from localStorage immediately after page load
    loadUserData: (context) => {
    context.commit('setUserData');
    },

    // Open/close the sidebar
    opensidebar: (context) => {
    context.commit('opensidebar');
    },

    // Handle resize toggle
    resizetoggle: (context) => {
    context.commit('resizetoggle');
    },

    // Set active state for bonus navigation
    setBonusNavActive: (context, term) => {
    context.commit('setBonusNavActive', term);
    },

    // Handle search term filtering
    searchTerm: (context, term) => {
    context.commit('searchTerm', term);
    },

    // Set active state for navigation
    setNavActive: (context, item) => {
    context.commit('setNavActive', item);
    },

    // Set active route for navigation
    setActiveRoute: (context, item) => {
    context.commit('setActiveRoute', item);
  }
};

export default {
    namespaced: true,
    state,
    getters: {},
    actions,
  mutations
};

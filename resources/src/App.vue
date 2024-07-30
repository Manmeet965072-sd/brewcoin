<template>
    <div>
        <page-header :configData="configData" />
        <page-menu :configData="configData" :usermenuData="usermenuData" />
        <div class="app-content content" :class="configData['pageClass']">
            <!-- BEGIN: Header-->
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>

            <div v-if="
                configData['contentLayout'] !== 'default' &&
                configData['contentLayout']
            " class="content-area-wrapper" :class="
    configData['layoutWidth'] === 'boxed'
        ? 'container-xxl p-0'
        : ''
">
                <div :class="configData['sidebarPositionClass']">
                    <div class="sidebar"></div>
                </div>
                <div :class="configData['contentsidebarClass']">
                    <div class="content-wrapper">
                        <div class="content-body">
                            <keep-alive>
                                <router-view v-if="user !== null" :user="user" :kyc="kyc">
                                </router-view>
                            </keep-alive>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="content-wrapper" :class="
                configData['layoutWidth'] === 'boxed'
                    ? 'container-xxl p-0'
                    : ''
            ">
                <div class="content-body" id="content-body">
                    <Transition type="animation" name="zoom-fade" mode="out-in" :duration="300">
                        <keep-alive>
                            <router-view v-if="user !== null" :user="user" :kyc="kyc">
                            </router-view>
                        </keep-alive>
                    </Transition>
                </div>
            </div>
        </div>
        <page-footer :configData="configData" />
    </div>
</template>

<script>

export default {
    // component list
    components: {
    },
    // component data
    data() {
        const configDat = {
            ...configData,
            pageClass: {
                ...configData.pageClass,
                "invisible": true
            }
        };

        return {
            mainComp: "",
            usermenuData: usermenuData,
            configData: configDat,
            user: null,
            kyc: null,
        };
    },
    // custom methods
    methods: {
        fetchData() {
            this.$http.post("/user/fetch/data").then((response) => {
                (this.kyc = response.data.kyc),
                    (this.user = response.data.user);

                if (!this.kyc) {
                    window.location.href = "/user/kyc";
                } else {
                    const configDat = {
                        ...this.configData,
                        pageClass: {
                            ...configData.pageClass,
                            "invisible": false
                        }
                    };

                    this.configData = configDat;
                }
            });
        },
        goBack() {
            window.history.length > 1
                ? this.$router.go(-1)
                : this.$router.push("/");
        },
    },
    created() {

     },
    // on component mounted
    mounted() { },

    // on component destroyed
    destroyed() { },
};
</script>
<style lang="scss">
.menu-expanded {
    .app-content {
        width: calc(100% - 260px);
        display: inline-block;
    }
}

// ///////////////////////////////////////////////
// Zoom Fade
// ///////////////////////////////////////////////
.zoom-fade-enter-active,
.zoom-fade-leave-active {
    transition: transform 0.35s, opacity 0.28s ease-in-out;
}

.zoom-fade-enter {
    transform: scale(0.97);
    opacity: 0;
}

.zoom-fade-leave-to {
    transform: scale(1.03);
    opacity: 0;
}

// ///////////////////////////////////////////////
// Fade Regular
// ///////////////////////////////////////////////
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.28s ease-in-out;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

// ///////////////////////////////////////////////
// Page Slide
// ///////////////////////////////////////////////
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: opacity 0.35s, transform 0.4s;
}

.slide-fade-enter {
    opacity: 0;
    transform: translateX(-30%);
}

.slide-fade-leave-to {
    opacity: 0;
    transform: translateX(30%);
}

// ///////////////////////////////////////////////
// Zoom Out
// ///////////////////////////////////////////////
.zoom-out-enter-active,
.zoom-out-leave-active {
    transition: opacity 0.35s ease-in-out, transform 0.45s ease-out;
}

.zoom-out-enter,
.zoom-out-leave-to {
    opacity: 0;
    transform: scale(0);
}

// ///////////////////////////////////////////////
// Fade Bottom
// ///////////////////////////////////////////////

// Speed: 1x
.fade-bottom-enter-active,
.fade-bottom-leave-active {
    transition: opacity 0.3s, transform 0.35s;
}

.fade-bottom-enter {
    opacity: 0;
    transform: translateY(-8%);
}

.fade-bottom-leave-to {
    opacity: 0;
    transform: translateY(8%);
}

// Speed: 2x
.fade-bottom-2x-enter-active,
.fade-bottom-2x-leave-active {
    transition: opacity 0.2s, transform 0.25s;
}

.fade-bottom-2x-enter {
    opacity: 0;
    transform: translateY(-4%);
}

.fade-bottom-2x-leave-to {
    opacity: 0;
    transform: translateY(4%);
}

// ///////////////////////////////////////////////
// Fade Top
// ///////////////////////////////////////////////

// Speed: 1x
.fade-top-enter-active,
.fade-top-leave-active {
    transition: opacity 0.3s, transform 0.35s;
}

.fade-top-enter {
    opacity: 0;
    transform: translateY(8%);
}

.fade-top-leave-to {
    opacity: 0;
    transform: translateY(-8%);
}

// Speed: 2x
.fade-top-2x-enter-active,
.fade-top-2x-leave-active {
    transition: opacity 0.2s, transform 0.25s;
}

.fade-top-2x-enter {
    opacity: 0;
    transform: translateY(4%);
}

.fade-top-2x-leave-to {
    opacity: 0;
    transform: translateY(-4%);
}

///////////////////////////////////////////////////////////
// transition-group : list;
///////////////////////////////////////////////////////////
.list-leave-active {
    position: absolute;
}

.list-enter,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

///////////////////////////////////////////////////////////
// transition-group : list-enter-up;
///////////////////////////////////////////////////////////
.list-enter-up-leave-active {
    transition: none !important;
}

.list-enter-up-enter {
    opacity: 0;
    transform: translateY(30px);
}
</style>

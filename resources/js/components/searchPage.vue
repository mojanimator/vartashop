<template>
    <div class=" ">
        <div class="row justify-content-center mx-5">
            <div class="col-md-3 card-body shadow-card rounded bg-gradient-light mt-3 ">
                <div class="   py-0 position-relative text-center">


                    <div class="input-group   align-items-baseline     "
                         dir="ltr">
                        <span id="button-search" @click="data.splice(0, data.length);params.page=1;getData()"
                              class="btn bg-gradient-primary   px-3 rounded-pill-left w-25 ">
                            <svg aria-hidden="true" class="svg-inline--fa fa-search fa-w-16" focusable="false"
                                 data-prefix="fa" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                      d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
                            </svg>
                            <!-- <i aria-hidden="true" class="fa fa-search"></i> Font Awesome fontawesome.com -->
                        </span>
                        <input id="search-input" type="text" placeholder="نام، برند، ویژگی" dir="rtl"
                               v-on:keyup.enter="data.splice(0, data.length);params.page=1;getData()"
                               aria-label="جست و جوی محصول"
                               aria-describedby="button-addon1" name="search" v-model="params.search"
                               class="form-control   px-5 py-2 rounded-pill-right" required>
                    </div>

                </div>
                <div class="accordion accordion-flush" id="accordionGroups">

                    <div v-for="g,idx in groups" class="accordion-item">
                        <h2 class="accordion-header " :id="'accordion-'+g.id">
                            <button class="accordion-button bg-gradient-dark text-white rounded"
                                    type="button"
                                    data-bs-toggle="collapse" :ref="'accordion-'+g.id"
                                    :data-bs-target="'#panel'+g.id" aria-expanded="true"

                                    aria-controls="panelsStayOpen-collapseOne">
                                <span class="d-flex flex-row justify-content-between w-100">
                                    <span>{{g.name}}</span>
                                    <i
                                            class="fa    fa-arrow-circle-down"
                                            aria-hidden="true"></i> </span>
                            </button>

                        </h2>
                        <div :id="'panel'+g.id" class="accordion-collapse collapse show pt-0 pb-2"
                             aria-labelledby="panelsStayOpen-headingOne">
                            <div class="accordion-body nav navbar-vertical p-1">
                                <ul class="list-group text-right p-0 w-100">
                                    <li class="list-group-item " v-for="gg,idx in g.childs">
                                        <div class="form-check form-switch ps-0 ">
                                            <input @change="data.splice(0, data.length);params.page=1;getData()"
                                                   class="form-check-input ms-auto mt-1 " type="checkbox"
                                                   dir="ltr" v-model="gg.selected"
                                                   :id="'check-group-'+gg.id">
                                            <label class="form-check-label ms-2"
                                                   :for="'check-group-'+gg.id">{{gg.name}}</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-9 card-body shadow-card">
                <div class="col-12 row text-right text-dark rounded bg-gradient-light p-2 mb-2 z-index-0  ">
                    <div class="col-3 hover-underline " :class="params.order_by=='created_at'?'active':''"
                         @click="params.order_by='created_at';params.dir='DESC';data.splice(0, data.length);params.page=1;getData();">
                        جدید ترین
                    </div>
                    <div class="col-3 hover-underline "
                         :class="params.order_by=='price'  && params.dir=='ASC'?'active':''"
                         @click="params.order_by='price';params.dir='ASC';data.splice(0, data.length);params.page=1;getData();">
                        ارزان ترین
                    </div>
                    <div class="col-3 hover-underline"
                         :class="params.order_by=='price' && params.dir=='DESC'?'active':''"
                         @click="params.order_by='price';params.dir='DESC';data.splice(0, data.length);params.page=1;getData();">
                        گران ترین
                    </div>

                    <div class="col-3 hover-underline" :class="params.order_by=='sold'?'active':''"
                         @click="params.order_by='sold';params.dir='DESC';data.splice(0, data.length);params.page=1;getData();">
                        محبوب ترین
                    </div>
                </div>
                <div class="col-12 row ">
                    <div v-for="d,idx in data " class="col-md-4 col-sm-6 col-lg-4 col-xl-3  p-1 ">

                        <div class="m-card  d-flex align-items-start align-content-around flex-column    "
                             data-toggle="modal"
                             :key="d.id">
                            <div class="m-card-header bg-transparent   ">
                                <span v-show="d.salePercent"
                                      class="sale-middle-left  shadow-lg bg-gradient-danger    ">{{d.salePercent}}</span>
                                <div class="d-flex justify-content-between position-relative  top-1 mx-1">

                                    <div class=" z-index-3   badge-pill bg-primary text-white   small-1"
                                         data-toggle="tooltip"
                                         data-placement="top"
                                         title="فروشنده"> {{d.shop.name}}

                                    </div>
                                    <!--<div v-show="d.discount_price > 0"-->
                                    <!--class=" z-index-3    "-->
                                    <!--data-toggle="tooltip"-->
                                    <!--data-placement="top"-->
                                    <!--title="تخفیف">-->
                                    <!--<i class="fas  fa-lg fa-fire  text-yellow  "></i>-->

                                    <!--</div>-->
                                </div>
                                <img class="back-header-img" :src="assetLink+'/card-header.png'" alt="">
                                <div class=" position-relative">
                                    <a :href="d.image" data-lity class="  ">
                                        <div class="    img-overlay">⌕</div>
                                        <img class="card-img  "
                                             @error="imgError"
                                             :src="d.image" alt="">
                                    </a>
                                </div>

                            </div>

                            <!--<img v-else src="img/school-no.png" alt=""></div>-->
                            <div class="m-card-body  px-2   flex-column align-self-stretch  text-end z-index-1">

                                <div class="text-primary my-1 text-center max-2-line "> {{d.name}}</div>

                                <div class="  d-flex justify-content-center pt-1">
                    <span class="rounded-right    bg-primary text-white small d-inline-block px-1 "
                    > کد محصول

                    </span>
                                    <span class="rounded-left    bg-secondary text-white small d-inline-block px-1 "
                                    > {{d.id}}

                    </span>

                                </div>

                                <div class="card-divider"></div>

                                <p class="card-text text-primary  text-lg">
                                    <i class="fas  fa-money-bill-alt"></i>
                                    <span class="mx-1"
                                          :class="d.discount_price>0? 'text-decoration-line-through':'' "
                                          v-text="separator(d.price)+' ت '">    </span>
                                    <span class="mx-1" v-show="d.discount_price>0"
                                          v-text="separator(d.discount_price)+' ت '">  </span>

                                </p>


                                <p class="card-text text-dark-blue p-type">
                                    <i class="fas  fa-arrow-circle-left"></i>

                                </p>


                                <div class="card-divider"></div>


                            </div>
                            <div class="m-card-footer  bg-transparent  ">
                                <img class="   back-footer-img" :src="assetLink+'/card-footer.png'" alt="">
                                <div class="  d-flex justify-content-center px-1  position-absolute bottom-1 z-index-3 w-100 text-center">
                                    <form @click="  updateCart(d.id)" :id="'cart-form-'+id+'-'+d.id" name="cart-add"
                                          :action="cartLink"
                                          method="post"
                                          class="rounded-right py-2    bg-gradient-success text-white small d-inline-block px-1 w-50 move-on-hover hoverable  ">
                                        <input type="hidden" name="_token" :value="csrf">
                                        <input type="hidden" name="cmnd" value="plus"/>
                                        <input type="hidden" name="id" :value="d.id"/>
                                        <input type="hidden" name="shop_id" :value="d.shop_id"/>
                                        <small class=""
                                        >
                                            <i class="fas  fa-cart-plus"></i>
                                            سبد خرید

                                        </small>

                                    </form>
                                    <a class="link-white rounded-left py-2   bg-gradient-info text-white small d-inline-block px-1 w-50 move-on-hover hoverable"
                                       :href="'/product/'+d.name+'/'+d.id">

                                        جزییات</a>
                                </div>
                            </div>

                        </div>


                    </div>
                    <h4 v-if=" noData" class="text-center mt-3 text-primary">
                        متاسفانه محصولی یافت نشد

                    </h4>
                    <div v-if=" noData" class="text-center text-primary"> اگر محصول خاصی میخواهید با ما تماس بگیرید
                    </div>
                </div>

                <div class="progress-line mt-1" :style="loading?'opacity:1;':'opacity:0;'"></div>
            </div>
        </div>
    </div>
</template>

<script>
    let scrolled = false;
    export default {
        props: ['dataLink', 'groupLink', 'imgLink', 'assetLink', 'rootLink', 'cartLink', 'params'],

//        components: {paginator, refEditor},
        data() {
            return {
                url: window.location.href.split('?')[0],
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                collapsed: false,
                data: [],
                scroll: true,
                paginate: 12,
                loading: false,
                total: -1,
                order_by: 'created_at',
                dir: 'DESC',
                groups: [],
                groups_show_tree: true,
                noData: false,
                page: 1,
            }
        },
        created() {


            this.getGroups();


        },
        watch: {
//            scroll(value, oldValue) {
//                console.log(value);
//                console.log(oldValue);
//
//            }
        },
        computed: {
//            dataChunk() {
//
//            }
        },
        mounted() {
            self = this;
            this.setEvents($(".progress-line"));

        }, methods: {
            log(str) {
                console.log(str);
            },
            imgError(event) {

                event.target.src = '/img/vartashop_logo.png';
                event.target.parentElement.href = '/img/noimage.png';
            },
            setEvents(el) {
                $(window).scroll(function () {

                    let top_of_element = el.offset().top;
                    let bottom_of_element = el.offset().top + el.outerHeight();
                    let bottom_of_screen = $(window).scrollTop() + $(window).innerHeight();
                    let top_of_screen = $(window).scrollTop();

                    if ((bottom_of_screen + 300 > top_of_element) && (top_of_screen < bottom_of_element + 200) && !self.loading && self.total > self.data.length) {
                        self.getData();
                        scrolled = true;
                        // the element is visible, do something
                    } else {
                        // the element is not visible, do something else
                    }
                });
            },

            isVisible() {
                $.fn.isInViewport = function () {
                    let elementTop = $(this).offset().top;
                    let elementBottom = elementTop + $(this).outerHeight();

                    let viewportTop = $(window).scrollTop();
                    let viewportBottom = viewportTop + $(window).height();

                    return elementBottom > viewportTop && elementTop < viewportBottom;
                };
            },
            separator(price) {
                return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            updateCart(id) {
                document.getElementById('cart-form-' + this.id + '-' + id).submit();
            },
            getData() {
//                this.log(this.params);

//                this.loading.removeClass('hide');
                this.loading = true;
                this.noData = false;


                this.params = {
                    search: this.params.search ? this.params.search : this.search,
                    page: !isNaN(this.params.page) ? this.params.page : this.page,
                    paginate: this.params.paginate ? this.params.paginate : this.paginate,
                    order_by: this.params.order_by ? this.params.order_by : this.order_by,
                    dir: this.params.dir ? this.params.dir : this.dir,
                    group_ids: this.groups.length > 0 ? this.groups.reduce((prev, next) => {

                        return typeof prev.childs !== 'undefined' ?
                            prev.childs.concat(next.childs) :
                            prev.concat(next.childs)
                    }).filter((el) => el.selected).map((el) => el.id) : [],

                };
                if (scrolled) {

                    this.params.page++;
                    scrolled = false;
                }

                history.replaceState(null, null, axios.getUri({url: this.url, params: this.params}));
//                this.log(this.params);
//                this.log(axios.getUri({url: this.url, params: this.params}));

                axios.get(this.dataLink, {
                    params: this.params
                })
                    .then((response) => {

//                            console.log(axios.getUri({url: this.url, params: response.config.params}));
//                        this.loading.addClass('hide');
                            if (response.status === 200) {


//                            console.log(response.data);
                                this.data = this.data.concat(response.data.data);

                                this.total = response.data.total;
//                                this.page = response.data.current_page + 1;

                                this.loading = false;
                                if (this.data.length === 0)
                                    this.noData = true;
                                if (this.params.page > 1 && this.data.length === 0) {
                                    this.noData = false;
                                    this.params.page = 1;
                                    this.getData();
                                }
                            }
                        }
                    ).catch((error) => {
                    console.log(error);
                    this.loading = false;
                });
            },
            getGroups() {
//                this.loading.removeClass('hide');

                axios.get(this.groupLink, {
                    params: {
                        ...{
                            show_tree: this.groups_show_tree,

                        },

                    }
                })
                    .then((response) => {
//                        console.log(response.data);

//                        this.loading.addClass('hide');
                        if (response.status === 200) {
//                            console.log(response.data);
                            this.groups = response.data;


                            this.params = {

                                search: this.params.search ? this.params.search : this.search,
                                page: this.params.page !== 'undefined' ? this.params.page : this.page,
                                paginate: this.params.paginate ? this.params.paginate : this.paginate,
                                order_by: this.params.order_by ? this.params.order_by : this.order_by,
                                dir: this.params.dir ? this.params.dir : this.dir,
                                group_ids: this.params.group_ids && this.params.group_ids.length > 0 ? this.params.group_ids : []

                            };

                            if (this.params.group_ids.length > 0)
                                for (let g in this.groups) {

                                    for (let idx in this.groups[g].childs) {

                                        if (this.params.group_ids.includes(this.groups[g].childs[idx].id.toString()))
                                            this.groups[g].childs[idx].selected = true;
                                    }
                                }
                            this.getData();

//                            cosnole.log(this.groups);

                        }
                    }).catch((error) => {
                    console.log(error);
                    this.loading = false;
                });
            }
            ,
        }
    }
</script>

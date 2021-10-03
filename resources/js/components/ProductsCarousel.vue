<template>


    <div :id="id" :ref="id" class="owl-carousel owl-theme text-center " :class="'owl-'+id">


        <!--<div v-for="d,idx in data2 " class="   " style="max-width: 15rem"-->
        <!--&gt;-->

        <!--<div class="m-card  d-flex align-items-start align-content-around flex-column    " data-toggle="modal"-->
        <!--:key="d.id">-->
        <!--<div class="m-card-header bg-transparent   ">-->
        <!--<div class="d-flex justify-content-between position-relative  top-1 mx-1">-->

        <!--<div class=" z-index-3   badge-pill bg-primary text-white   small-1"-->
        <!--data-toggle="tooltip"-->
        <!--data-placement="top"-->
        <!--title="فروشنده"> {{d.shop.name}}-->

        <!--</div>-->
        <!--<div v-if="d.discount_price > 0"-->
        <!--class=" z-index-3    "-->
        <!--data-toggle="tooltip"-->
        <!--data-placement="top"-->
        <!--title="تخفیف">-->
        <!--<i class="fas  fa-lg fa-fire  text-yellow  "></i>-->

        <!--</div>-->
        <!--</div>-->
        <!--<img class="back-header-img" :src="assetLink+'/card-header.png'" alt="">-->
        <!--<div class=" position-relative">-->
        <!--<a :href="imgLink+'/'+d.img" data-lity class="  ">-->
        <!--<div class="    img-overlay">⌕</div>-->
        <!--<img class="card-img  " :src="imgLink+'/'+d.img" alt="">-->
        <!--</a>-->
        <!--</div>-->

        <!--</div>-->

        <!--&lt;!&ndash;<img v-else src="img/school-no.png" alt=""></div>&ndash;&gt;-->
        <!--<div class="m-card-body  px-2   flex-column align-self-stretch  text-end z-index-1">-->

        <!--<div class="text-primary my-1 text-center max-2-line "> {{d.name}}</div>-->

        <!--<div class="  d-flex justify-content-center pt-1">-->
        <!--<span class="rounded-right    bg-primary text-white small d-inline-block px-1 "-->
        <!--&gt; کد محصول-->

        <!--</span>-->
        <!--<span class="rounded-left    bg-secondary text-white small d-inline-block px-1 "-->
        <!--&gt; {{d.id}}-->

        <!--</span>-->

        <!--</div>-->

        <!--<div class="card-divider"></div>-->

        <!--<p class="card-text text-primary  text-lg">-->
        <!--<i class="fas  fa-money-bill-alt"></i>-->
        <!--<span class="mx-1"-->
        <!--:class="d.discount_price>0? 'text-decoration-line-through':'' "-->
        <!--v-text="separator(d.price)+' ت '">    </span>-->
        <!--<span class="mx-1" v-show="d.discount_price>0"-->
        <!--v-text="separator(d.discount_price)+' ت '">  </span>-->

        <!--</p>-->


        <!--<p class="card-text text-dark-blue p-type">-->
        <!--<i class="fas  fa-arrow-circle-left"></i>-->

        <!--</p>-->


        <!--<div class="card-divider"></div>-->


        <!--</div>-->
        <!--<div class="m-card-footer  bg-transparent  ">-->
        <!--<img class="   back-footer-img" :src="assetLink+'/card-footer.png'" alt="">-->
        <!--<div class="  d-flex justify-content-center px-1  position-absolute bottom-1 z-index-3 w-100 text-center">-->
        <!--<form @click="  updateCart(d.id)" :id="'cart-form'+d.id" name="cart-add" :action="cartLink"-->
        <!--method="post"-->
        <!--class="rounded-right py-2    bg-gradient-success text-white small d-inline-block px-1 w-50 move-on-hover hoverable  ">-->
        <!--<input type="hidden" name="_token" :value="csrf">-->
        <!--<input type="hidden" name="cmnd" value="plus"/>-->
        <!--<input type="hidden" name="id" :value="d.id"/>-->
        <!--<small class=""-->
        <!--&gt;-->
        <!--<i class="fas  fa-cart-plus"></i>-->
        <!--سبد خرید-->

        <!--</small>-->

        <!--</form>-->
        <!--<a class="link-white rounded-left py-2   bg-gradient-info text-white small d-inline-block px-1 w-50 move-on-hover hoverable"-->
        <!--:href="'/product/'+d.slug+'/'+d.id">-->

        <!--جزییات</a>-->
        <!--</div>-->
        <!--</div>-->

        <!--</div>-->


        <!--</div>-->

    </div>


</template>

<script>
    let self;

    let owlRefresh = false;
    export default {


        props: ['dataLink', 'imgLink', 'assetLink', 'rootLink', 'cartLink', 'id', 'params',],

//        components: {paginator, refEditor},
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                data: [],
                owl: null,

                loading: null,
                page: 1,
                total: 99999999999999999999,
                paginate: 8,
                loadedPages: 1,
//                doc: null,
                carouselIndex: 0,
            }
        }
        ,
//        watch: {
//            data(value, oldValue) {
//
//
//            }
//        },
        computed: {
//            dataChunk() {
//                let chunk = [];
//                let a = [];
//                let i;
//                for (i = 1; i <= this.data.length; i++) {
//
//                    a.push(this.data[i - 1]);
//                    console.log(a);
//                    if (i % 4 === 0) {
//                        chunk.push(a.slice());
//                        a.splice(0, a.length);
//                    }
//                }
////                if (i - 1 % 4 !== 0  ) {
////                    chunk.push(a.slice());
//////                    a.splice(0, a.length);
////                }
//                console.log(chunk);
//                return chunk;
//            }
        }
        ,

        created() {
            self = this;

            this.getData();

        },
        mounted() {


            this.setEvents();


        },

        updated() {



//
//            console.log('update');
//            if (owl === undefined) {
//            owl.trigger('destroy.owl.carousel');
//            owlRefresh = true;
//            owl.trigger('add.owl.carousel', ['<div class="item"><p>' + 'hh' + '</p></div>']).trigger('refresh.owl.carousel');

        },
        methods: {
            addToCarousel(data, vueId) {
                let html;
                for (let idx in data) {
                    let d = data[idx];

                    html = `

                <div class="m-card  d-flex align-items-start align-content-around flex-column    " data-toggle="modal" style="max-width: 15rem"
                v-bind:key="${d.id}"     >
                    <div class="m-card-header bg-transparent   ">
                      <span class="sale-middle-left  shadow-lg bg-gradient-danger  ${d.salePercent ? '' : 'd-none'}">${d.salePercent}</span>
                        <div class="d-flex justify-content-between position-relative  top-1 mx-1">

                            <div class=" z-index-3   badge-pill bg-primary text-white   small-1"
                                 data-toggle="tooltip"
                                 data-placement="top"
                                 title="فروشنده">  ${d.shop.name}

                            </div>
                            <div
                                 class=" z-index-3   ${d.discount_price > 0 ? 'd-none' : 'd-none'} "
                                 data-toggle="tooltip"
                                 data-placement="top"
                                 title="تخفیف">
                                <i class="fas  fa-lg fa-fire  text-yellow  "></i>

                            </div>
                        </div>
                        <img class="back-header-img" src="${this.assetLink + '/card-header.png'}" alt="">
                        <div class=" position-relative">
                            <a href="${this.imgLink + '/' + d.img}" data-lity class="  ">
                                <div class="    img-overlay">⌕</div>
                                <img class="card-img  " src="${this.imgLink + '/' + d.img}" alt="">
                            </a>
                        </div>

                    </div>

                    <!--<img v-else src="img/school-no.png" alt=""></div>-->
                    <div class="m-card-body  px-2   flex-column align-self-stretch  text-end z-index-1">

                        <div class="text-primary my-1 text-center max-2-line "> ${d.name}</div>

                        <div class="  d-flex justify-content-center pt-1">
                            <span class="rounded-right    bg-primary text-white small d-inline-block px-1 "
                            > کد محصول

                            </span>
                            <span class="rounded-left    bg-secondary text-white small d-inline-block px-1 "
                            > ${d.id}

                            </span>

                        </div>

                        <div class="card-divider"></div>

                        <p class="card-text text-primary  text-lg">
                            <i class="fas  fa-money-bill-alt"></i>
                            <span class="mx-1 ${d.discount_price > 0 ? 'text-decoration-line-through' : ''} "

                                >${this.separator(d.price) + ' ت '}    </span>
                            <span class="mx-1 ${d.discount_price > 0 ? '' : 'd-none'}"
                                  > ${this.separator(d.discount_price) + ' ت '} </span>

                        </p>


                        <p class="card-text text-dark-blue p-type">
                            <i class="fas  fa-arrow-circle-left d-none"></i>

                        </p>


                        <div class="card-divider"></div>


                    </div>
                    <div class="m-card-footer  bg-transparent  ">
                        <img class="   back-footer-img" src="${this.assetLink + '/card-footer.png'}" alt="">
                        <div class="  d-flex justify-content-center px-1  position-absolute bottom-1 z-index-3 w-100 text-center">
                            <form id="${'cart-form-' + this.id + '-' + d.id}"  name="cart-add" action="${this.cartLink}"
                                  method="post"
                                  class="rounded-right py-2    bg-gradient-success text-white small d-inline-block px-1 w-50 move-on-hover hoverable  ">
                                <input type="hidden" name="_token" value="${this.csrf}">
                                <input type="hidden" name="cmnd" value="plus"/>
                                <input type="hidden" name="id" value="${d.id}"/>
                                <input type="hidden" name="shop_id" value="${d.shop_id}"/>
                                <small class=""
                                >
                                    <i class="fas  fa-cart-plus"></i>
                                    سبد خرید

                                </small>

                            </form>
                            <a class="link-white rounded-left py-2   bg-gradient-info text-white small d-inline-block px-1 w-50 move-on-hover hoverable"
                               href="${'/product/' + d.slug + '/' + d.id}">

                                مشاهده</a>
                        </div>
                    </div>

                </div>


           `;


                    this.owl.trigger('add.owl.carousel', html);
                    $('#cart-form-' + vueId + '-' + d.id).on('click',
                        function () {
                            $('#cart-form-' + vueId + '-' + d.id).submit()
                        }
                    );
                }


            },
            separator(price) {
                return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            initCarousel(self) {


                this.owl.owlCarousel({
                    nav: true,
                    lazyLoad: true,
                    margin: 8,
                    itemClass: 'owl-item',
                    stagePadding: 24,
                    infinite: true,
                    pagination: true,
                    startPosition: self.carouselIndex,
                    dots: true,
                    rewind: false,
//                    autoWidth: self.data.length > 4,
                    autoHeight: true,
//                        itemElement: 'div',
                    navContainer: false,// '#' + this.id + '-nav',//String/Class/ID/Boolean
                    navText: [

                        "<span class='   carousel-control-next-icon  rounded-right bg-dark p-2 my-1 hoverable '></span>",
                        "<span class='   carousel-control-prev-icon rounded-left bg-dark p-2 my-1 hoverable'></span>",

                    ],
//                        responsiveClass: true,
                    items: 8,
                    rtl: true,
                    onChanged: function (event,) {
//                        let element = event.target;         // DOM element, in this example .owl-carousel
//                        let name = event.type;           // Name of the event, in this example dragged
//                        let namespace = event.namespace;      // Namespace of the event, in this example owl.carousel
//                        let items = event.item.count;     // Number of items
//                        let item = event.item.index;     // Position of the current item
//                        // Provided by the navigation plugin
//                        let pages = event.page.count;     // Number of pages
//                        let page = event.page.index;     // Position of the current page
//                        let size = event.page.size;
//                        console.log(event.page.size);
//                        console.log(event.page.index);
//                        console.log(event.page.count);
//                    console.log(event.item);


                        if (owlRefresh) {
                            owlRefresh = false;

                            return;
                        }
//                        console.log(self.data.length);
//                        console.log(self.total);
//                        if (/*event.page.index !== -1 &&*/ event.page.index + 3 >= event.page.count && self.data.length < self.total) {
                        if (event.item.index !== null && event.item.index + 7 >= event.item.count && self.data.length < self.total) {
//                            self.carouselIndex = event.item.index;
//                            console.log('onChanged');
                            self.getData();

                        }
                    }, responsiveClass: false,
                    responsive: {
                        0: {
                            items: 1,
                            nav: true
                        }, 599: {
                            items: 1,
                            nav: true
                        },
                        600: {
                            items: 2,
                            nav: true
                        },
                        768: {
                            items: 3,
                            nav: true,
//                            loop: false
                        },
                        992: {
                            items: 4,
                            nav: true,
//                            loop: false
                        },
                        1200: {
                            items: 5,
                            nav: true,

//                            loop: false
                        },
                        1600: {
                            items: 6,
                            nav: true,

//                            loop: false
                        }
                    }
                });

            },

            updateCart(id) {


                document.getElementById('cart-form-' + this.id + '-' + id).submit();


            },
            getData() {
//                this.loading.removeClass('hide');

                axios.get(this.dataLink, {
                    params: {
                        ...{
                            page: this.page,
                            paginate: this.paginate,
                        },
//                    direction: this.direction,
//                    sortBy: this.orderBy,
                        ...this.params
                    }
                })
                    .then((response) => {
//                        console.log(response.data);

//                        this.loading.addClass('hide');
                        if (response.status === 200) {
//                            console.log(response.data);
                            this.data = this.data.concat(response.data.data);
                            this.total = response.data.total;

                            this.page++;

                            this.$nextTick(() => {
//                                owl.trigger('add.owl.carousel');
                                if (!this.owl) {
//                                    console.log('tick');
                                    this.owl = $("#" + this.id);
                                    this.initCarousel(this);
                                    this.addToCarousel(response.data.data, this.id);
                                    this.owl.trigger('refresh.owl.carousel');


                                }
                                else {
                                    this.addToCarousel(response.data.data, this.id);
                                    this.owl.trigger('refresh.owl.carousel');

//                        owl.trigger('destroy.owl.carousel');
//
//                        this.initCarousel();

                                }
                            });


                        }
                    }).catch((error) => {
                    console.log(error);

                });
            }
            ,

            setEvents() {
//                this.$root.$on('paginate_click', data => {
//                    this.page = data['page'];
//                    this.getRefs();
//                });

                $(document).ready(function () {

                });

//                let carouselVitrin = document.getElementById(self.id);
//
//                let carouselVitrinObj = new bootstrap.Carousel(carouselVitrin, {
//                    interval: false,
//                    wrap: false
//                });
//                carouselVitrin.addEventListener('slid.bs.carousel', function (e) {
//                    carouselVitrinObj.interval = false;
//                    console.log(e);
//                    self.getData();
//                });


                //loader for images
                $(".card-img").each((i, el) => {

                    let imgTag = el;
                    let img = new Image();
                    img.src = imgTag.src;
//                    imgTag.src = '';
                    $(imgTag).addClass('loading');
                    img.onload = () => {
                        imgTag.src = './img/noimage.png';

//                        $(imgTag).removeClass('loading');
                        imgTag.setAttribute('src', img.src);
                        // Fade out and hide the loading image.
//                $('.loading').fadeOut(100); // Time in milliseconds.
                    };
                    img.onerror = (e) => {
//                console.log(e);
                        $(imgTag).removeClass('loading');
                        $(imgTag).prop('src', './img/noimage.png');
                    };

                });
            }
        }
    }
</script>

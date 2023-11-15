<template>
    <div class="row col-12">

        <!-- Modal -->
        <div class="modal fade text-right " :id="'modal'" tabindex="-1"
             aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="exampleModalLabel">{{ selectedProd.name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div v-if="cmnd=='name'">
                            <label :for="'modal-'+cmnd"> ویرایش نام</label>
                            <input :id="'modal-'+cmnd" class="form-group form-control" type="text"
                                   v-model="modifiedProd.name">
                        </div>
                        <div v-if="cmnd=='price'">
                            <label :for="'modal-'+cmnd"> ویرایش قیمت (تومان)</label>
                            <input :id="'modal-'+cmnd" class="form-group form-control" type="number"
                                   v-model="modifiedProd.price">
                        </div>
                        <div v-if="cmnd=='discount_price'">
                            <label :for="'modal-'+cmnd"> ویرایش قیمت حراج (تومان)</label>
                            <input :id="'modal-'+cmnd" class="form-group form-control" type="number"
                                   v-model="modifiedProd.discount_price">
                        </div>
                        <div v-if="cmnd=='count'">
                            <label :for="'modal-'+cmnd"> ویرایش تعداد </label>
                            <input :id="'modal-'+cmnd" class="form-group form-control" type="number"
                                   v-model="modifiedProd.count">
                        </div>
                        <div v-if="cmnd=='active'">
                            <label :for="'modal-'+cmnd"> وضعیت </label>
                            <input :id="'modal-'+cmnd" class="   " type="checkbox"
                                   v-model="modifiedProd.active">
                        </div>
                        <div v-if="cmnd=='description'">
                            <label :for="'modal-'+cmnd"> توضیحات </label>
                            <textarea :id="'modal-'+cmnd" class="form-group form-control" rows="5"
                                      v-model="modifiedProd.description"></textarea>
                        </div>
                        <div v-if="cmnd=='tags'">
                            <label :for="'modal-'+cmnd"> هشتگ ها ( هر هشتگ در یک خط جداگانه و بدون # نوشته شود) </label>
                            <textarea :placeholder="['کرم','پوست','مرطوب کننده','کرم اصل'    ].join('\n')"
                                      :id="'modal-'+cmnd" class="form-group form-control" rows="5"
                                      :ref="'modal-'+cmnd"
                                      :value="modifiedProd.tags.replaceAll('#','').replaceAll('_',' ')"
                            ></textarea>
                        </div>
                        <div v-if="cmnd=='group_id'">
                            <label :for="'modal-'+cmnd">
                                دسته بندی (در صورت موجود نبودن دسته مرتبط، به ادمین اطلاع دهید) </label>
                            <div class="row mx-2">
                                <select :id="'modal-'+cmnd" class="form-group form-control w-45  px-1  "
                                        v-model="group1"
                                        @change="group2=null">
                                    <option class="text-dark" v-for="group in getGroups({level:1})"
                                            :value="group.id">
                                        {{ group.name }}

                                    </option>
                                </select>
                                <span class="w-10 align-self-baseline text-center">
                                    <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
                                </span>
                                <select :id="'modal-'+cmnd" class="form-group form-control  w-45 px-1  "
                                        v-model="group2" @change="group1=getGroups({id:group2})[0].parent">
                                    <option class="text-dark" v-for="group in getGroups({level:2,parent:group1})"
                                            :value="group.id">
                                        {{ group.name }}

                                    </option>
                                </select>
                            </div>
                        </div>
                        <div v-if="cmnd=='del-prod'">
                            <h5 class="text-danger"> از حذف این محصول اطمینان دارید؟</h5>

                        </div>
                        <div v-if="cmnd=='img'">
                            <image-uploader
                                :for-id="modifiedProd.id"
                                :link="editLink"
                                :callback="  function a(){return getImages(modifiedProd.id);} "
                                height="10rem" mode="edit"
                            >

                            </image-uploader>
                            <div class="border border-light row  ">
                                <div v-for="img in images" class="col-3" :key="img">
                                    <a :href="storage+'/products/'+img+'.jpg'" data-lity class="  ">
                                        <img :src="storage+'/products/'+img+'.jpg'" alt="" @error="imgError"
                                             class="mw-100 rounded">
                                    </a>
                                    <button
                                        @click.prevent="confirmDelete('del-img',img);"
                                        type="button"
                                        class="btn btn-danger  py-1 px-2 hoverable-purple position-absolute left-0  top-0 z-index-3">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <img id="loading" src="/img/loading.gif"
                         class="d-none position-fixed z-index-3  left-0 right-0  mx-auto d-hi "
                         width="200"
                         alt="">
                    <div v-show="cmnd!=='img' && cmnd !=='del-img' " class="  row  px-4 btn-group" dir="ltr">
                        <button type="button"

                                class="btn btn-secondary col-6 px-1" data-bs-dismiss="modal">لغو
                        </button>
                        <button @click="editData(cmnd)" type="button" class="btn  col-6 px-1"
                                :class="cmnd != 'del-prod' ? 'btn-primary' : 'btn-danger'">
                            {{ cmnd != 'del-prod' ? 'ثبت' : 'حذف' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!--search containers-->
        <div class="  row align-items-center   ">

            <div class="col-9">
                <div class="input-group   align-items-baseline  p-2    "
                     dir="ltr">

                    <span id="button-search" @click.prevent="products.splice(0, products.length);page=1;getData()"
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
                           v-on:keyup.enter="products.splice(0, products.length);page=1;getData()"

                           aria-label="جست و جوی محصول"
                           aria-describedby="button-addon1" name="search" v-model="search"
                           class="form-control   px-5 py-2  " required>
                    <span
                        @click.prevent="search=null;products.splice(0, products.length);page=1;getData()"
                        class="btn bg-gradient-danger rounded-pill-right  align-self-center">
                            <i class="fa  fa-window-close" aria-hidden="true"></i>
                        </span>
                </div>
            </div>
            <div class="col-3">
                <select class="form-group form-control px-3    rounded-pill "
                        v-model="shopId"
                        @change="products.splice(0, products.length);page=1;getData()"
                >
                    <option class="text-dark " :value="shop.id" v-for="shop in shop_ids">
                        {{ shop.name }}

                    </option>
                </select>
            </div>
        </div>


        <!--cards-->
        <div v-for="prod,idx in products" class="col-md-6 py-1 " :key="prod.id">
            <button data-bs-toggle="modal" data-bs-target="#modal"
                    @click.prevent="cmnd='del-prod';modifiedProd=selectedProd=prod;selectedIdx=idx;" type="button"
                    class="btn btn-danger   px-3 hoverable-purple position-absolute left-0 m-2 top-0 z-index-3">
                <i class="fa fa-trash" aria-hidden="true"></i>
            </button>


            <!--<a :href="productLink+'/product?id='+prod.id" class="   ">-->
            <div class="card  move-on-hover">
                <div class="card-body p-3 ">
                    <div class="row ">
                        <div class="  mb-0 text-right font-weight-bold text-primary text-sm ">

                                        <span data-bs-toggle="modal" data-bs-target="#modal"
                                              class="text-primary hoverable-dark px-2 rounded-pill"
                                              @click.prevent="openModal('name',idx)">

                                             {{ prod.name }}
                                        </span>
                        </div>
                        <div class="col-8">
                            <div class="numbers">

                                <hr class="horizontal dark m-0 mt-1">
                                <div class="d-flex justify-content-between  text-black-50   pt-1 px-1">
                                        <span data-bs-toggle="modal" data-bs-target="#modal"
                                              class="text-info hoverable-dark px-2 rounded-pill"
                                              @click.prevent="openModal('price',idx)">
                                            <span class="text-dark small">قیمت:</span>
                                            {{ separator(prod.price) }}
                                        </span>
                                    <span data-bs-toggle="modal" data-bs-target="#modal"
                                          class="text-info hoverable-dark px-2 rounded-pill"
                                          @click.prevent="openModal('count',idx)">
                                            <span class="text-dark small ">تعداد:</span>
                                            {{ prod.count }}
                                        </span>

                                </div>
                                <div class="d-flex justify-content-between  text-black-50   pt-1 px-1">
                                         <span data-bs-toggle="modal" data-bs-target="#modal"
                                               class="text-info hoverable-dark px-2 rounded-pill"
                                               @click.prevent="openModal('discount_price',idx)">
                                            <span class="text-dark small">قیمت حراج:</span>
                                            {{ separator(prod.discount_price) }}
                                        </span>
                                    <span data-bs-toggle="modal" data-bs-target="#modal"
                                          class="  hoverable-primary px-2 rounded-pill "
                                          :class=" prod.active ? 'text-success' : 'text-danger'"
                                          @click.prevent="openModal('active',idx)">

                                            {{ prod.active ? 'فعال' : 'غیر فعال' }}
                                        </span>

                                </div>
                                <div class="d-flex justify-content-between  text-black-50   pt-1 px-1">
                                         <span data-bs-toggle="modal" data-bs-target="#modal"
                                               class="text-info hoverable-dark px-2 rounded-pill"
                                               @click.prevent="openModal('group_id',idx)">
                                            <span class="text-dark small">دسته بندی:</span>
                                            {{ prod.group ? prod.group.name : 'نامشخص' }}
                                        </span>


                                </div>

                            </div>
                        </div>
                        <div class="col-4 text-end">


                            <img :src="prod.image" @error="imgError"
                                 data-bs-toggle="modal" data-bs-target="#modal"
                                 @click.prevent="openModal('img',idx)"
                                 class="mw-100 rounded move-on-hover cursor-pointer" height="64" width="64" alt="">


                        </div>
                        <div @click="openModal('description',idx)" data-bs-toggle="modal" data-bs-target="#modal"
                             class="rounded-1 small text-right border border-light  hoverable-dark    pt-1 px-1">
                                         <span
                                             class="text-indigo small  p-2 rounded-pill"
                                         >

                                            {{
                                                 prod.description && prod.description.length > 100 ? prod.description.substring(0, 100) + ' ...' : prod.description
                                             }}
                                        </span>


                        </div>
                        <div @click="openModal('tags',idx)" data-bs-toggle="modal" data-bs-target="#modal"
                             class="rounded-1 small text-right border border-light  hoverable-dark   mt-1 pt-1 px-1">

                                <span
                                    class="text-pink small  p-2 rounded-pill"
                                >

                                            {{ prod.tags }}
                                        </span>


                        </div>
                    </div>
                </div>
            </div>
            <!--</a>-->
        </div>
        <div class="progress-line mt-1" :style="loading?'opacity:1;':'opacity:0;'"></div>
    </div>
</template>

<script>
let scrolled = false;
let self;
import imageUploader from './image-uploader.vue';

export default {
    props: ['storage', 'imagesLink', 'searchLink', 'productLink', 'editLink', 'shopIds', 'groupsLink'],

    components: {imageUploader,},
    data() {
        return {
            prod: null,
            products: [],
            collapsed: false,
            selectedProd: {},
            modifiedProd: {},
            cmnd: null,
            selectedIdx: null,

            scroll: true,
            loading: false,
            total: -1,
            order_by: 'created_at',
            dir: 'DESC',
            groups: [],
            groups_show_tree: true,
            noData: false,
            errors: '',
            attach: {},
            group1: null,
            group2: null,
            images: [],

            search: null,
            paginate: 12,
            page: 1,
            shop_ids: [{id: 0, name: 'همه فروشگاههای من'}, ...JSON.parse(this.shopIds)],
            shopId: 0,
        }
    },
    created() {
        // this.getData();
        this.getGroups();
        self = this;

    },
    watch: {

        loading: function (val) {
            if (val) {
                $('#loading').removeClass('d-none');
            } else
                $('#loading').addClass('d-none');
        },
    },
    mounted() {
        $("#modal").on("hidden.bs.modal", function () {
            if (!self.hidedByOkButton) {
                self.products[self.selectedIdx] = self.selectedProd;
                self.$forceUpdate();
            } else {
                self.hidedByOkButton = true;
            }
        });
        this.setEvents($(".progress-line"));
    },
    methods: {
        log(str) {
            console.log(str);
        },
        imgError(event) {

            event.target.src = '/img/vartashop_logo.png';
            event.target.parentElement.href = '/img/noimage.png';
        },
        confirmDelete(cmnd, id) {
            window.showDialog('confirm', 'از حذف تصویر اطمینان دارید؟', () => this.editData(cmnd, id));
        },
        openModal(cmnd, idx) {

            this.cmnd = cmnd;
            this.modifiedProd = this.products[idx];
            this.selectedProd = {...this.modifiedProd};
            this.selectedIdx = idx;
            if (cmnd == 'group_id') {
                if (this.modifiedProd.group && this.modifiedProd.group.level == 1)
                    this.group1 = this.modifiedProd.group.id;
                if (this.modifiedProd.group && this.modifiedProd.group.level == 2) {
                    this.group2 = this.modifiedProd.group.id;
                    this.group1 = this.modifiedProd.group.parent;
                }
            }
            if (cmnd == 'img') {
                this.getImages(this.modifiedProd.id);
            }
        },
        setEvents(el) {
            $(window).scroll(function () {

                let top_of_element = el.offset().top;
                let bottom_of_element = el.offset().top + el.outerHeight();
                let bottom_of_screen = $(window).scrollTop() + $(window).innerHeight();
                let top_of_screen = $(window).scrollTop();
                if ((bottom_of_screen + 300 > top_of_element) && (top_of_screen < bottom_of_element + 200) && !self.loading /*&& self.total > self.products.length*/) {
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
        getImages(id) {
//                this.log(this.params);

//
            this.loading = true;

            axios.get(this.imagesLink, {
                params: {
                    p_id: id,

                }
            })
                .then((response) => {

                        if (response.status === 200) {
                            this.loading = false;
                            this.images = response.data;
                            this.modifiedProd.image = this.images.length > 0 ? (this.storage + '/products/' + this.images[0] + '.jpg') : '/img/vartashop-logo.png';
                        }
                    }
                ).catch((error) => {

                console.log(error);
                this.loading = false;
                return [];
            });
        },
        getData() {
            // console.log(this.page);

            if (this.total > 0 && this.total <= this.products.length) return;
            this.loading = true;

            if (scrolled) {

                this.page++;
                scrolled = false;
            }


//                this.log(this.shop_ids);

            axios.get(this.searchLink, {
                params: {
                    order_by: this.order_by,
                    dir: this.dir,
                    shop_ids: this.shopId === 0 ? this.shop_ids.map((el) => el.id) : [this.shopId],
                    search: this.search,
                    page: this.page,
                    paginate: this.paginate,
                }
            })
                .then((response) => {

//                            console.log(axios.getUri({url: this.url, params: response.config.params}));
//                        this.loading.addClass('hide');
                        if (response.status === 200) {
                            // console.log(this.page);


                            // console.log(response.data);
                            this.products = this.products.concat(response.data.data);

                            this.total = response.data.total;
                            // this.page = response.data.current_page + 1;

                            this.loading = false;
                            if (this.products.length === 0)
                                this.noData = true;
                            if (this.page > 1 && this.products.length === 0) {
                                this.noData = false;
                                this.page = 1;
                                this.getData();
                            }
//                                this.openModal('img', 0);
//                                $("#modal").modal("show");
                        }
                    }
                ).catch((error) => {

                console.log(error);
                this.loading = false;
            });
        },
        editData(cmnd, id) {
            this.hidedByOkButton = true;

            this.loading = true;
            switch (cmnd) {
                case 'name':
                    this.attach = {name: this.modifiedProd.name};
                    break;
                case 'count':
                    this.attach = {count: this.modifiedProd.count};
                    break;
                case 'price':
                    this.attach = {price: this.modifiedProd.price};
                    break;
                case 'discount_price':
                    this.attach = {discount_price: this.modifiedProd.discount_price};
                    break;
                case 'description':
                    this.attach = {description: this.modifiedProd.description};
                    break;
                case 'tags':
//                        console.log(this.$refs['modal-tags'].value);
                    this.attach = {
                        tags: this.$refs['modal-tags'].value.replace(' ', '_').replace('#', '').split('\n').map((el) => {
                            if (el.endsWith('_'))
                                el = el.slice(0, -1);
                            if (el !== '')
                                return '#' + el;
                        }).join('\n')
                    };
                    break;
                case 'active':
                    this.attach = {active: this.modifiedProd.active};
                    break;
                case 'group_id':
                    this.attach = {group_id: this.group2 ? this.group2 : this.group1};
                    break;
                case 'del-img':
                    this.cmnd = cmnd;
                    this.attach = {img_id: id};

                    break;
            }

            axios.post(this.editLink, {
                id: this.modifiedProd.id,
                cmnd: this.cmnd,
                ...this.attach

            })
                .then((response) => {

                        if (this.cmnd !== 'del-img')
                            $('#modal').modal('hide');
                        if (response.status === 200) {

                            this.products[this.selectedIdx] = this.selectedProd = this.modifiedProd;
                            if (this.cmnd === 'del-prod') {

//                                    this.$set(this.products, this.selectedIdx);
//                                    this.$delete(this.products, this.selectedIdx);

//                                    Vue.delete(this.products, this.selectedIdx);
                                this.products.splice(this.selectedIdx, 1);
                            } else if (this.cmnd === 'tags') {
                                this.products[this.selectedIdx].tags = this.attach.tags;
                            } else if (this.cmnd === 'group_id') {
                                this.products[this.selectedIdx].group = this.getGroups({id: this.attach.group_id})[0];
                            } else if (this.cmnd === 'del-img') {


                                this.getImages(this.modifiedProd.id);
                                this.cmnd = 'img';

//                                    $('#modal').modal("show");
                            }

//                                this.page = response.data.current_page + 1;


                        }
                        this.loading = false;
//                            this.$forceUpdate();
                    }
                ).catch((error) => {
//                    $('#modal').modal('hide');
//                    console.log(error.response.data.errors);
                this.loading = false;
                this.errors = '';
                if (error.response && error.response.status === 422)
                    for (let idx in error.response.data.errors)
                        this.errors += '' + error.response.data.errors[idx] + '<br>';
                else {
                    this.errors = error;
                }
                window.showDialog('danger', this.errors, onclick = null);
            });
        },
        getGroups(items = {show_tree: null, id: null, level: null, parent: null}) {
//                this.loading.removeClass('hide');

            if (this.groups.length === 0) {
                axios.get(this.groupsLink, {
                    params: {
                        ...{
                            items

                        },

                    }
                })
                    .then((response) => {
//                            console.log(response.data);

//                        this.loading.addClass('hide');
                        if (response.status === 200) {
//                            console.log(response.data);
                            this.groups = response.data;


//                            cosnole.log(this.groups);

                        }
                    }).catch((error) => {
                    console.log(error);
                    this.loading = false;
                });
            } else {
                return this.groups.filter(((el) => {
                        let chain = true;
                        if (items.level)
                            chain = items.level == el.level;
                        if (items.parent && chain)
                            chain = items.parent == el.parent;
                        if (items.id && chain)
                            chain = items.id == el.id;
                        return chain;
                    }

                ).bind(items));


            }
        }
        ,
    }
}
</script>

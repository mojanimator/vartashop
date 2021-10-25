<template>
    <div class="  ">


        <div class="col-10 mx-auto  text-primary text-center  my-1  font-weight-bold ">جزییات سفارش</div>


        <div class="col-10 card text-right border-info border-2 my-3 py-2    ">
            <div class="card-header py-2 ">

                <div class="   position-absolute   w-100 justify-content-center" style="top:-.7rem;">
                    <div class="input-group mx-2">
                                <span class="rounded-right    bg-primary text-white small d-inline-block px-1 "
                                > شماره سفارش

                            </span>
                        <span class="rounded-left    bg-dark text-white small d-inline-block px-1 ">
                        {{ order.id}}

                            </span>
                    </div>


                </div>
                <div class="input-group mb-1  ">
                                <span class="rounded-right    bg-danger text-white small d-inline-block px-1 "
                                > وضعیت سفارش

                            </span>
                    <span class="rounded-left    bg-dark text-white small d-inline-block px-2 ">
                        {{ order.statustext}}

                            </span>
                </div>
                <div v-if="editable" class="input-group position-absolute justify-content-end left-1"
                     style="top:-.7rem;">

                    <div class="    rounded-pill  bg-danger text-white small   p-2 move-on-hover cursor-pointer"
                         @click="showDeleteDialog()">
                        لغو سفارش

                    </div>

                </div>
                <form id="delete-order" method="POST"
                      :action="deleteOrderLink">
                    <input type="hidden" name="_token" :value="csrf">
                    <input type="hidden" name="order_id" :value=" order.id">
                    <input type="hidden" name="shop_id" :value=" order.shop_id">
                    <input type="hidden" name="order_user_id" :value="order.user_id">
                    <input type="hidden" name="order_status" :value="order.status">
                </form>


                <div class="input-group ">
                    <span class="rounded-right    bg-info text-white small d-inline-block px-1">تاریخ ثبت  </span>
                    <span class="rounded-left    bg-dark text-white small d-inline-block px-1">{{order.createdat}}</span>
                </div>
                <div class="border-bottom  border-info border-2  py-1 "></div>
                <div class="row  ">
                    <div class="col-sm-6">
                        <span class="text-primary">گیرنده: </span>

                        <input v-if="editable" type="text" v-model="order.name" class="px-4   form-control "
                               :class="errors.name? 'is-invalid':''">
                        <div v-else="">{{order.name}}</div>

                        <span v-if="errors.name" class="col-12 small  text-danger p-2" role="alert">
                                        <strong>{{ errors.name[0] }}</strong>
                      </span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-primary">شماره تماس: </span>

                        <input v-if="editable" type="tel" v-model="order.phone" class="px-4   form-control "
                               :class="errors.phone? 'is-invalid':''">
                        <div v-else="">{{order.phone}}</div>

                        <span v-if="errors.phone" class="col-12 small  text-danger p-2" role="alert">
                                        <strong>{{ errors.phone[0] }}</strong></span>

                    </div>
                </div>

                <div class="row  ">
                    <div class="col-sm-6">
                        <span class="text-primary">استان: </span>
                        <select v-if="editable" class="px-4 form-control "
                                :class="errors.province_id? 'is-invalid':''"
                                v-model="order.province.id"
                                @change="order.county.id=null">
                            <option class="text-dark" v-for="province in provinces"
                                    :value="province.id">
                                {{ province.name }}

                            </option>
                        </select>
                        <div v-else=""> {{ order.province.name }}</div>
                        <span v-if="errors.province_id" class="col-12 small  text-danger p-2" role="alert">
                                        <strong>{{ errors.province_id[0] }}</strong></span>

                    </div>
                    <div class="col-sm-6">
                        <span class="text-primary">شهر: </span>
                        <select v-if="editable" class="px-4 form-control "
                                :class="errors.county_id? 'is-invalid':''"
                                v-model="order.county.id"
                                @change=" ">
                            <option class="text-dark"
                                    v-for="county in counties.filter(function(el) {return order.province.id==el.province_id;} )"
                                    :value="county.id">
                                {{ county.name }}

                            </option>
                        </select>
                        <div v-else=""> {{ order.county.name }}</div>
                        <span v-if="errors.county_id" class="col-12 small  text-danger p-2" role="alert">
                                        <strong>{{ errors.county_id[0] }}</strong></span>

                    </div>

                </div>
                <div class="row col-sm-6  ">
                    <span class="text-primary">کد پستی: </span>
                    <input v-if="editable" type="tel" v-model="order.postal_code"
                           :class="errors.postal_code? 'is-invalid':''"
                           class="px-4   form-control  ">
                    <div v-else=""> {{ order.postal_code }}</div>
                    <span v-if="errors.postal_code" class="col-12 small  text-danger p-2" role="alert">
                                        <strong>{{ errors.postal_code[0] }}</strong></span>

                </div>
                <div class=" col-12">
                    <span class="text-primary">آدرس: </span>
                    <textarea v-if="editable" v-model="order.address" class="col-12 form-control px-4"
                              :class="errors.address? 'is-invalid':''" rows="3"></textarea>
                    <div v-else=""> {{ order.address }}</div>
                    <span v-if="errors.address" class="col-12 small  text-danger p-2" role="alert">
                                        <strong>{{ errors.address[0] }}</strong></span>

                </div>
                <span class="text-primary">توضیحات  </span>
                <div class="border border-info rounded p-2">
                    <div v-if="order.description!=null">
                    <span v-for="chat,idx in order.description.split('$|$')"
                          class="text-dark ">

                        <div class="text-dark small" :class="idx%2==0? 'ml-2':'mr-2'">
                             <span v-if="idx%2==0"><i class="fa fa-user text-info" aria-hidden="true"></i></span>
                             <span v-else="idx%2==0"><i class="fa fa-user-tie text-primary"
                                                        aria-hidden="true"></i></span>
                            {{chat}}
                        </div>
                    </span>
                    </div>
                    <textarea v-if="order.status==1 || order.status==3" v-model="appendDescription"
                              :class="errors.description? 'is-invalid':''"
                              class="col-12 form-control mt-2" rows="2"></textarea>
                    <span v-if="errors.description" class="col-12 small  text-danger p-2" role="alert">
                                        <strong>{{ errors.description[0] }}</strong></span>

                </div>
                <div class="border-bottom  border-info border-2  py-1 "></div>

                <div class="row">
                    <div v-if="order.shop" class="col-sm-6 my-1  ">
                        <div class="row align-items-baseline">
                            <div class="col-3">
                                <a :href="shopLink">
                                    <img
                                            :src=" order.shop.image "
                                            alt=""
                                            class="rounded-circle " style="max-width: 3rem">
                                </a>

                            </div>
                            <div class="col-9   align-items-center justify-content-between">
                                <a :href="shopLink">
                                    <div class="hoverable-text-blue my-1">{{order.shop.name}}</div>
                                </a>

                            </div>

                        </div>
                    </div>
                    <div v-if="editable" class="col-sm-6">
                        <div class="btn  btn-success btn-block my-1"
                             data-bs-toggle="modal" data-bs-target="#modal">
                            اضافه کردن محصول
                            <i class="fa   fa-plus-circle text-white  "
                               aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div v-for="product,idx in order.products"
                         :key="product.id"
                         class="col-12 col-sm-4 col-md-3    border rounded border-light p-1 text-center">

                        <button v-if="editable"
                                @click.prevent="order.products.splice(idx,1)"
                                type="button"
                                class="btn btn-danger    px-2 py-1 hoverable-purple position-absolute left-0 m-2 top-0 z-index-3">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                        <div class="d-flex flex-column justify-content-between   h-100">
                            <a :href="productLink+'/'+product.name+'/'+product.id  "
                               :title="product.name" target="_blank" data-toggle="tooltip"
                               class=" m-1 ">
                                <img :src="product.image" alt="" @error="imgError" width="64" height="64"
                                     class="  rounded-lg  ">
                                <div class="small">{{product.name}}</div>
                                <span v-if="errors[`products.${idx}.product_id`] "
                                      class="col-12 small  text-danger p-2" role="alert">

                                        <strong>{{errors[`products.${idx}.product_id`][0] }}</strong></span>
                            </a>
                            <div class="d-flex flex-column justify-content-between">
                                <div class="text-primary">
                                    <span class="text-dark small">تعداد:&nbsp</span>
                                    <input v-if="editable" type="number" class="form-control px-4"
                                           v-model="product.pivot.qty">
                                    <div v-else=""> {{product.pivot.qty }}</div>
                                    <span v-if="errors[`products.${idx}.qty`] "
                                          class="col-12 small  text-danger p-2" role="alert">

                                        <strong>{{errors[`products.${idx}.qty`][0] }}</strong></span>

                                </div>
                                <div class="text-primary">
                                    <span class="text-dark small"> قیمت واحد:&nbsp</span>
                                    <input v-if="editable" type="number" class="form-control px-4"
                                           v-model="product.pivot.unit_price">
                                    <div v-else=""> {{product.pivot.unit_price }}</div>
                                    <span v-if="errors[`products.${idx}.unit_price`] "
                                          class="col-12 small  text-danger p-2" role="alert">

                                        <strong>{{errors[`products.${idx}.unit_price`][0] }}</strong></span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-bottom  border-info border-2  py-1 "></div>

                <div class="col-12 row justify-content-between">
                    <div class="col-sm-6">
                        <span class="text-primary">قیمت کالاها: </span>
                        <span class="text-dark">{{separator(order.total_price) }}</span>

                    </div>
                    <div class="col-sm-6">
                        <span class="text-primary">هزینه پست: </span>
                        <input v-if="editable" type="number" class="form-control" v-model="order.post_price">
                        <span v-else=""> {{order.post_price}}</span>
                    </div>
                    <div class="col-sm-6  ">
                        <span v-if="order.pay_id" class="text-primary">شناسه پرداخت: </span>
                        <input v-if="editable && order.pay_id" type="number" class="form-control px-4"
                               v-model="order.pay_id">
                        <span v-else="" class="text-dark">{{ order.pay_id}}</span>

                    </div>
                    <div class="col-sm-6  ">

                        <span class="text-primary">مبلغ پرداختی: </span>
                        <span class="text-dark">{{ totalPrice()}}</span>
                    </div>
                </div>
                <span v-if="errors.common"
                      class="col-12 small  text-danger p-2" role="alert">

                                        <strong>{{errors.common[0] }}</strong></span>

            </div>
            <div v-if="order.status == 1" class="btn btn-block bg-gradient-info text-lg mx-auto col-12 mt-2"
                 @click="editData();">ثبت سفارش
                {{ '  ( آماده پرداخت )  ' }}
            </div>

            <div v-if="order.status == 3" class="row btn-group  " dir="ltr">
                <div class="btn btn-block bg-gradient-danger text-lg mt-1  col-6 "
                     @click="showDialog('confirm','هزینه سفارش را دریافت نکرده ام و سفارش را به وضعیت در انتظار پرداخت تغییر می دهم.',function() {
                       editData(2);
                     })"> عدم تایید پرداخت

                </div>
                <div class="btn btn-block bg-gradient-success text-lg mt-1  col-6  "
                     @click="showDialog('confirm','هزینه سفارش را دریافت کرده ام و در حال ارسال به مشتری هستم.',function() {
                       editData(4);
                     })">ارسال سفارش

                </div>
            </div>
        </div>

        <div class="modal fade text-right " id="modal" tabindex="-1"
             aria-labelledby="products order"
             aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="exampleModalLabel">اضافه کردن محصول به سفارش</h5>
                        <button type="button" class="btn-close text-danger" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="input-group   align-items-baseline  p-2  w-75 mx-auto   "
                         dir="ltr">
                    <span id="button-search"
                          @click.prevent="searchProducts.splice(0, searchProducts.length);page=1;getProducts()"
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
                               @keyup.enter="searchProducts.splice(0, searchProducts.length);page=1;getProducts()"
                               aria-label="جست و جوی محصول"
                               aria-describedby="button-addon1" name="search" v-model="search"
                               class="form-control   px-4 py-2     ">

                    </div>
                    <div class="overflow-scroll p-2 m-2 " style="max-height: 16rem" id="scroll">
                        <div v-for="product,idx in searchProducts" class="d-flex  p-1 border-bottom">
                            <img :src="product.image" @error="imgError"
                                 class="  rounded move-on-hover   " height="64" width="64" alt="">
                            <div class="small w-100 px-1">{{product.name}}</div>
                            <div class="btn bg-green align-self-center " @click="addToOrder(product)">
                                <i class="fa fa-plus text-white" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="progress-line mt-1" :style="loading?'opacity:1;':'opacity:0;'"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    let scrolled = false;
    let self;
    export default {
        props: ['order', 'productLink', 'deleteOrderLink', 'shopLink', 'searchLink', 'provinces', 'counties', 'editLink', 'editable'],
        data() {
            return {

                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                errors: {},
                searchProducts: [],
                loading: false,
                total: -1,
                page: 1,
                search: null,

            }
        }, watch: {

            loading: function (val) {
                if (val) {
                    $('#loading').removeClass('d-none');
                } else
                    $('#loading').addClass('d-none');
            },
        },
        created() {
            this.order = JSON.parse(this.order);
            this.provinces = JSON.parse(this.provinces);
            this.counties = JSON.parse(this.counties);
            this.getProducts();

            self = this;
        },
        mounted() {
//            $(document).ready(function () {
//                $('#modal').modal('show');
//            });
            this.setScrollEvents($(".progress-line"), $("#scroll"));

        }, methods: {
            addToOrder(product) {

                let found = this.order.products.filter((el) => el.id === product.id).length > 0;
                if (!found) {
                    product.pivot = {
                        order_id: this.order.id,
                        product_id: product.id,
                        qty: 1,
                        unit_price: product.price > 0 ? product.price : product.discount_price
                    };
                    this.order.products.push(product);

                }
            },
            totalPrice() {

                let total = 0;
                for (let idx in this.order.products) {

                    if (isNaN(this.order.products[idx].pivot.unit_price))
                        return '?';
                    total += ( this.order.products[idx].pivot.unit_price * this.order.products[idx].pivot.qty);
                }

                if (this.order.post_price === null)
                    this.order.post_price = 0;
                if (isNaN(this.order.post_price))
                    return '?';
                this.order.total_price = total;
                total += +this.order.post_price;

                return this.separator(total, '') + ' تومان ';
            }, log(str) {
                console.log(str);
            },
            imgError(event) {

                event.target.src = '/img/vartashop_logo.png';
//                event.target.parentElement.href = '/img/noimage.png';
            },
            setEvents(el) {

            },

            separator(price, label = ' ت ') {
                if (price)
                    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + label;
                else return '?';
            },
            showDeleteDialog() {
                window.showDialog('confirm', 'سفارش لغو و حذف خواهد شد', () => {
                    document.getElementById('delete-order').submit();
                })
            },
            showDialog(type, msg, func) {
                window.showDialog(type, msg, func)
            },

            editData(status = 0) {
                let params;
                let desc;
                this.loading = true;
                if (this.appendDescription)
                    desc = (this.order.description === null ? '' : this.order.description) + this.appendDescription + '$|$';

                else desc = this.order.description;
                if (status === 0) {
                    params = {
                        id: this.order.id,
                        name: this.order.name,
                        phone: this.order.phone,
                        postal_code: this.order.postal_code,
                        address: this.order.address,
                        user_id: this.order.user_id,
                        shop_id: this.order.shop_id,
                        province_id: this.order.province.id,
                        county_id: this.order.county.id,
                        status: this.order.status,
                        post_price: this.order.post_price,
                        total_price: this.order.total_price,
                        products: this.order.products.map((el) => {
                                return {
                                    product_id: el.id,
                                    unit_price: el.pivot.unit_price,
                                    qty: el.pivot.qty
                                };
                            }
                        ),
                        description: desc
                    }
                } else {
                    params = {
                        cmnd: 'status',
                        status: status,
                        id: this.order.id,
                        user_id: this.order.user_id,
                        shop_id: this.order.shop_id,
                        description: desc
                    };
                }
                axios.post(this.editLink,

                    params
                )
                    .then((response) => {


                            if (response.status === 200) {
                                this.showDialog('success-flash', "سفارش  با موفقیت ویرایش شد");
                                window.location = '/panel/my-orders';

//                                this.page = response.data.current_page + 1;


                            }
                            this.loading = false;
//                            this.$forceUpdate();
                        }
                    ).catch((error) => {
                    this.errors = {};
//                    $('#modal').modal('hide');

                    this.loading = false;


                    this.errors = error.response.data.errors != null ? error.response.data.errors : {};
//                    this.errors = '';
//                    if (error.response && error.response.status === 422)
//                        for (let idx in error.response.data.errors)
//                            this.errors += '' + error.response.data.errors[idx] + '<br>';
//                    else {
//                        this.errors = error;
//                    }
//                    window.showDialog('danger', this.errors, onclick = null);
                });
            },
            getProducts() {

                if (this.total > 0 && this.total <= this.searchProducts.length) return;
                this.loading = true;

                if (scrolled) {

                    this.page++;
                    scrolled = false;
                }


                axios.get(this.searchLink, {
                    params: {

                        shop_ids: [this.order.shop.id],
                        search: this.search,
                        page: this.page,
                        order_by: 'created_at',

                    }
                })
                    .then((response) => {

//                            console.log(axios.getUri({url: this.url, params: response.config.params}));
//                        this.loading.addClass('hide');
                            if (response.status === 200) {


//                            console.log(response.data);
                                this.searchProducts = this.searchProducts.concat(response.data.data);

                                this.total = response.data.total;
//                                this.page = response.data.current_page + 1;

                                this.loading = false;
                                if (this.searchProducts.length === 0)
                                    this.noData = true;
                                if (this.page > 1 && this.searchProducts.length === 0) {
                                    this.noData = false;
                                    this.page = 1;
                                    this.getProducts();
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
            setScrollEvents(el, parent) {

                $(parent).scroll(function () {

                    let top_of_element = el.offset().top;
                    let bottom_of_element = el.offset().top + el.outerHeight();
                    let bottom_of_screen = parent.scrollTop() + parent.innerHeight();
                    let top_of_screen = parent.scrollTop();

                    if ((bottom_of_screen + 300 > top_of_element) && (top_of_screen < bottom_of_element + 200) && !self.loading && self.total > self.searchProducts.length) {
                        self.getProducts();
                        scrolled = true;
                        // the element is visible, do something
                    } else {
                        // the element is not visible, do something else
                    }
                });
            },
        }
    }
</script>

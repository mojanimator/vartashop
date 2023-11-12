<template>
    <div class="row col-12">


        <form class=" card justify-content-center  form-group  mx-2 " id="form-create-product" method="POST"
              :action="createLink">
            <div class="card-body text-right ">
                <div class="row ">
                    <image-uploader
                            class="col-sm-6 mx-auto" id="img" :label="'تصاویر محصول ( حداکثر '+ imgLimit+' تصویر )'"
                            for-id="img"
                            link="null"
                            preload="null"
                            height="10rem"
                            mode="multi"
                            :limit="imgLimit"
                            :images="images"
                    >

                    </image-uploader>
                    <div class="border border-light row p-2 ">
                        <div v-for="img,idx in images" class="col-3" :key="img.id">
                            <a :href=" img.src " data-lity class="mb-1  ">
                                <img :src=" img.src " alt="" @error="imgError"
                                     class="mw-100 rounded">
                            </a>
                            <button
                                    @click.prevent="images.splice(idx, 1);"
                                    type="button"
                                    class="btn btn-danger  py-1 px-2 hoverable-purple position-absolute left-0  top-0 z-index-3">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                            <span v-if="errors['images.'+idx]" class="col-12 small  text-danger p-2"
                                  :class="errors.shop_id? 'is-invalid':''" role="alert">
                                        <strong>{{ errors['images.' + idx][0] }}</strong>
                                    </span>
                        </div>
                    </div>
                    <span v-if="errors.images" class="col-12 small  text-danger p-2"
                          :class="errors.images? 'is-invalid':''"
                          role="alert">
                                        <strong>{{ errors.images[0] }}</strong>
                                    </span>
                </div>

                <div class="row mt-4">
                    <div class="form-group row      ">
                        <div class="form-group  col-sm-6  ">
                            <label for="name-input"
                                   class="  col-form-label text-right"> نام محصول </label>

                            <div class="align-items-stretch flex-row d-flex  ">
                                <input id="name-input" type="text"
                                       class="border  px-4 form-control"
                                       :class="errors.name? 'is-invalid':''"
                                       name="name"
                                       v-model="params.name"
                                       autocomplete="name"
                                       autofocus>
                            </div>
                            <span v-if="errors.name" class="col-12 small  text-danger p-2"
                                  :class="errors.name? 'is-invalid':''" role="alert">
                                        <strong>{{ errors.name[0] }}</strong>
                                    </span>


                        </div>
                        <div class="form-group col-sm-6">
                            <label for="shop-id-input"
                                   class="  col-form-label text-right"> نام فروشگاه </label>
                            <select id="shop-id-input" class="  form-control px-3  "
                                    :class="errors.shop_id? 'is-invalid':''"
                                    v-model="params.shop_id">
                                <option class="text-dark " :value="shop.id" v-for="shop in shop_ids">
                                    {{shop.name}}
                                </option>
                            </select>
                            <span v-if="errors.shop_id" class="col-12 small  text-danger p-2"
                                  :class="errors.shop_id? 'is-invalid':''" role="alert">
                                        <strong>{{ errors.shop_id[0] }}</strong>
                                    </span>
                        </div>
                    </div>
                    <div class="form-group row      ">
                        <div class="   w-45  px-1">
                            <label for="group1-input"
                                   class=" col-form-label text-right"> دسته بندی (سطح 1) </label>
                            <select id="group1-input" class="px-4 form-control "
                                    :class="errors.group_id? 'is-invalid':''"
                                    v-model="group1"
                                    @change="group2=null">
                                <option class="text-dark" v-for="group in getGroups({level:1})"
                                        :value="group.id">
                                    {{group.name}}

                                </option>
                            </select>
                        </div>
                        <span class="w-10  align-self-end text-center">
                                    <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
                                </span>
                        <div class="   w-45  px-1">
                            <label for="group2-input"
                                   class="  col-form-label text-right"> دسته بندی (سطح 2) </label>
                            <select id="group2-input" class=" px-4 form-control    "
                                    v-model="group2" @change="group1=getGroups({id:group2})[0].parent">
                                <option class="text-dark" v-for="group in getGroups({level:2,parent:group1})"
                                        :value="group.id">
                                    {{group.name}}

                                </option>
                            </select>
                        </div>

                        <span v-if="errors.group_id" class="col-12 small  text-danger p-2"
                              :class="errors.group_id? 'is-invalid':''"
                              role="alert">
                                        <strong>{{ errors.group_id[0] }}</strong>
                                    </span>
                    </div>
                    <div class="form-group row      ">
                        <div class="col-sm-4">
                            <label for="price-input" class="col-form-label"> قیمت (تومان)</label>
                            <input id="price-input" class="  form-control px-4"
                                   :class="errors.price? 'is-invalid':''"
                                   type="number"
                                   v-model="params.price">
                            <span v-if="errors.price" class="col-12 small  text-danger p-2"
                                  role="alert">
                                        <strong>{{ errors.price[0] }}</strong>
                                    </span>
                        </div>
                        <div class="col-sm-4">
                            <label for="discount-price-input" class="col-form-label"> قیمت تخفیف (تومان)</label>
                            <input id="discount-price-input" class="   form-control px-4"
                                   :class="errors.discount_price? 'is-invalid':''"
                                   type="number"
                                   v-model="params.discount_price">
                            <span v-if="errors.discount_price" class="col-12 small  text-danger p-2"
                                  role="alert">
                                        <strong>{{ errors.discount_price[0] }}</strong>
                                    </span>
                        </div>
                        <div class="col-sm-4">
                            <label for="count-input" class="col-form-label"> تعداد</label>
                            <input id="count-input" class="   form-control px-4" type="number"
                                   :class="errors.count? 'is-invalid':''"
                                   v-model="params.count">
                            <span v-if="errors.count" class="col-12 small  text-danger p-2"
                                  role="alert">
                                        <strong>{{ errors.count[0] }}</strong>
                                    </span>
                        </div>

                    </div>
                    <div class="form-group row      ">
                        <label for="description-input" class="col-form-label"> توضیحات </label>
                        <textarea id="description-input" class=" px-4 form-control" rows="3"
                                  v-model="params.description" :class="errors.description? 'is-invalid':''">

                        </textarea>
                        <span v-if="errors.description" class="col-12 small  text-danger p-2"
                              role="alert">
                                        <strong>{{ errors.description[0] }}</strong>
                                    </span>
                    </div>
                    <div class="form-group row      ">
                        <label for="tags-input" class="col-form-label">
                            هشتگ ها ( هر هشتگ در یک خط جداگانه و بدون # نوشته شود)
                        </label>
                        <textarea :placeholder="['کرم','پوست','مرطوب کننده','کرم اصل'    ].join('\n')"
                                  id="tags-input" class="form-group form-control px-4" rows="3"
                                  v-model="tags" :class="errors.tags? 'is-invalid':''"
                        ></textarea>
                        <span v-if="errors.tags" class="col-12 small  text-danger p-2"
                              role="alert">
                                        <strong>{{ errors.tags[0] }}</strong>
                                    </span>
                    </div>


                    <div class="    text-lg btn     bg-gradient-success" @click.prevent="createProduct()">
                        ساخت محصول
                    </div>
                </div>

            </div>

        </form>
    </div>
</template>

<script>
    let self;
    import imageUploader from './image-uploader.vue';

    export default {
        props: ['productLink', 'createLink', 'shopIds', 'groupsLink', 'imgLimit'],

        components: {imageUploader,},
        data() {
            return {
                selectedIdx: null,
                loading: false,
                groups: [],
                errors: '',
                group1: null,
                group2: null,

                shop_ids: JSON.parse(this.shopIds),
                shopId: 0,


                tags: '',
                images: [],
                params: {
                    name: null,
                    shop_id: null,
                    description: null,
                    price: 0,
                    discount_price: 0,
                    count: 0,
                },
            }
        },
        created() {

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

        },
        methods: {
            log(str) {
                console.log(str);
            },
            imgError(event) {

                event.target.src = '/img/vartashop_logo.png';
                event.target.parentElement.href = '/img/noimage.png';
            },
            confirmDelete(id) {
                window.showDialog('confirm', 'از حذف تصویر اطمینان دارید؟', () => this.editData('del-img', id));
            },


            createProduct() {
                this.loading = true;

                axios.post(this.createLink, {
                    ...this.params,
                    images: this.images.map((el) => el.src),
                    group_id: this.group2 ? this.group2 : this.group1,
                    tags: this.tags.replace(' ', '_').replace('#', '').split('\n').map((el) => {
                        if (el.endsWith('_'))
                            el = el.slice(0, -1);
                        if (el !== '')
                            return '#' + el;
                    }).join('\n')
                })
                    .then((response) => {

                            this.loading = false;

                            if (response.status === 200) {
                                window.location.reload();

                            }
//                            this.$forceUpdate();
                        }
                    ).catch((error) => {
//                    $('#modal').modal('hide');
//                    console.log(error.response.data.errors);
                    this.loading = false;
                    this.errors = error.response.data.errors;
//                    if (error.response && error.response.status === 422)
//                        for (let idx in error.response.data.errors)
//                            this.errors += '' + error.response.data.errors[idx] + '<br>';
//                    else {
//                        this.errors = error;
//                    }
//                    window.showDialog('danger', this.errors, onclick = null);
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

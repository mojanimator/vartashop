<template>

    <div class="   ">

        <div class="     my-1 row col-12">
            <label :for="id"
                   class="col-12 col-form-label text-right">{{label}} </label>
            <div
                    class="  uploader-container   col-sm-6  mx-auto "
                    style="border:dashed"
                    role="form" @mouseover="uploader.addClass('hover');"
                    @dragover.prevent="uploader.addClass('hover');" @dragleave.prevent="uploader.removeClass('hover');"
                    @mouseleave=" uploader.removeClass('hover');"
                    @drop.prevent="uploader.removeClass('hover');filePreview($event  ) "
                    @click="openFileChooser($event,id+'-file')">

                <small class=" p-2   ">
                    عکس را اینجا بکشید یا کلیک کنید
                </small>
                <input v-show="false" :id="id+'-file'" class="col-12    " accept=".png, .jpg, .jpeg" type="file"
                       :name="id+'-file'" @input="filePreview($event )"/>
                <input :id="id" class="col-12" :name="id" type="hidden"/>


            </div>
            <div v-show="doc" class="    rounded-lg    col-sm-6">
                <img v-show="doc" :id="'img-'+id" class=" mw-100    " @error="doc=null" @load="initCropper()"
                     :src="doc" :style="'width:'+height/*+';height:'+height*/"
                     alt=""/>
                <div class="btn-group my-1 w-100  " role="group" dir="ltr">
                    <div v-if="mode=='edit'" class="btn p-2 bg-danger text-white m-0"
                         title="حذف تصویر" data-bs-toggle="tooltip" data-bs-placement="top"
                         @click="removeImage()">
                        <i class="fa fa-window-close text-white" aria-hidden="true"></i>
                    </div>
                    <div v-if="mode=='edit'" class="btn p-2 bg-success text-white m-0"
                         title="آپلود تصویر" data-bs-toggle="tooltip" data-bs-placement="top"
                         @click="uploadImage()">
                        <i class="fa fa-upload text-white" aria-hidden="true"></i>
                    </div>
                    <div v-else="" class="btn btn-block p-2 bg-success text-white m-0"
                         title="برش تصویر" data-bs-toggle="tooltip" data-bs-placement="top"
                         @click="cropImage()">
                        <i class="fa fa-2x fa-crop-alt text-white" aria-hidden="true"></i>
                    </div>
                </div>
            </div>

        </div>


        <div class="  row col-12 ">


            <!--cropper and qr canvas-->
            <!--<canvas id="myCanvas"></canvas>-->
            <input id="x" name="x" type="hidden"/>
            <input id="y" name="y" type="hidden"/>
            <input id="width" name="width" type="hidden"/>
            <input id="height" name="height" type="hidden"/>


        </div>

    </div>


</template>

<script>


    let doc = null;
    let image = null;
    let input = null;

    let self;
    let canvas;


    import Cropper from 'cropperjs';


    export default {


        props: ['link', 'id', 'height', 'preload', 'label', 'mode', 'forId', 'callback', 'images', 'limit'],
        components: {},
        data() {
            return {
                star: null,
                doc: this.preload,

                cropper: null,
                reader: null,
                loading: false,


                creating: false,
                removing: false,
                uploader: null,

                errors: "",
            }
        },
        watch: {
            doc: function (val) {
//                console.log('val');
                if (val) {
//                    this.initCropper();
                }
            },
            loading: function (val) {
//                console.log('val');
                if (val) {
                    $('#loading').removeClass('d-none');
                } else
                    $('#loading').addClass('d-none');
            },
        },
        beforeDestroy() {
//            console.log("beforeDestroy")
        },
        computed: {
//            get_noe_faza: () => {
//                return Vue.noe_faza;
//            }
        },
        mounted() {
            self = this;
            image = document.getElementById('img-' + this.id);
//            console.log(image);
//            $(".point-sw")

            this.uploader = $('#' + this.id + '-file');


        }
        ,
        created() {

        }
        ,
        updated() {


            if (!this.creating)
                this.initCropper();
//            this.AwesomeQRCode = AwesomeQRCode;
//            console.log(window.AwesomeQRCode)
        },
        beforeUpdate() {
        }
        ,
        methods: {
            cropImage() {
                this.loading = true;

                this.cropper.crop();
                this.loading = false;
                let img = this.cropper.getCroppedCanvas().toDataURL();
                if (this.mode === 'multi') {
                    if (this.images.length >= this.limit) {
                        window.showDialog('danger', 'تعداد تصاویر بیش از حد مجاز است', onclick = null);
                        return;
                    }
                    this.images.push({id: this.images.length, src: img});
                    this.doc = null;

                    this.initCropper();
                } else {
                    $('#' + self.id).val(img);
                    window.showDialog('success', 'تصویر آماده ارسال است', onclick = null);
                }
//                this.cropper.getCroppedCanvas().toBlob((blob) => {
//                    this.loading = false;
//                    if (blob) {
//
//
//                        $('#' + self.id).val(blob);
//
//                        window.showDialog('success', 'تصویر آماده ارسال است', onclick = null);
//                    }
//
//
//                });

            },
            removeImage() {

                axios.post(this.link, {
                    'shop_id': this.forId,
                    'cmnd': 'del-image',
                },)
                    .then((response) => {
//                            console.log(response);
                        this.loading = false;
                        window.location.reload();
//                            this.data = response.data;
//                            console.log(this.data);
//                        this.filteredData = this.data;
                    }).catch((error) => {
                    this.loading = false;
                    if (error.response && error.response.status === 422)
                        for (let idx in error.response.data.errors)
                            this.errors += error.response.data.errors[idx] + '<br>';
                    else {
                        this.errors = error;
                    }
                    window.showDialog('danger', this.errors, onclick = null);
                });
            }
            ,

            async uploadImage() {
//                console.log(this.$refs.dropdown.selected.length);

                this.loading = true;

                this.cropper.crop();
                let forId = this.forId;
                this.cropper.getCroppedCanvas().toBlob((blob) => {
                    let fd = new FormData();
                    fd.append('img', blob, forId + ".jpg");
                    fd.append('id', this.forId);

                    axios.post(this.link, fd,)
                        .then((response) => {
//                            console.log(response);
                            this.loading = false;
                            if (!this.callback)
                                window.location.reload();
                            else {
                                this.doc = null;
                                this.callback();
                            }
//                            this.data = response.data;
//                            console.log(this.data);
//                        this.filteredData = this.data;
                        }).catch((error) => {
                        this.loading = false;
                        if (error.response && error.response.status === 422)
                            for (let idx in error.response.data.errors)
                                this.errors += error.response.data.errors[idx] + '<br>';
                        else {
                            this.errors = error;
                        }
                        window.showDialog('danger', this.errors, onclick = null);
                    });
                });

            },
            initCropper() {


                if (this.cropper)
                    this.cropper.destroy();

                this.cropper = new Cropper(image, {
//                    autoCrop: false,
                    autoCropArea: 1,
                    viewMode: 2,
//                    autoCrop: true,
                    aspectRatio: 1,
                    crop(event) {
//                        console.log('crop');
                        self.creating = true;

                    },
                    cropend(event) {
//                        console.log('croped');
                    },
                    ready(e) {
                        if (self.mode !== 'edit') {
                            self.cropper.crop();
                            $('#' + self.id).val(self.cropper.getCroppedCanvas().toDataURL());
                        }
//                        console.log(e.type);

                        // this.cropper[method](argument1, , argument2, ..., argumentN);
//                        this.cropper.move(1, -1);

                        // Allows chain composition
//                        this.cropper.move(1, -1).rotate(45).scale(1, -1);
                    },
                });


            }
            ,

            openFileChooser(event, from) {
//                send fake click for browser file
                let image_input = document.getElementById(from);
                if (document.createEvent) {
                    let evt = document.createEvent("MouseEvents");
                    evt.initEvent("click", false, true);
                    image_input.dispatchEvent(evt);

                } else {
                    let evt = new Event("click", {"bubbles": false, "cancelable": true});
                    image_input.dispatchEvent(evt);
                }
            }
            ,
            filePreview(event) {
                let file;
//                console.log(e);
                if (event.dataTransfer) {
                    file = event.dataTransfer.files[0];
                }
                else if (event.target.files) {
                    file = event.target.files[0];
                }

//                    console.log(files.length);
//                        console.log(files.length);
                this.reader = new FileReader();
                this.reader.onload = function (e) {

                    doc = e.target.result;
                    self.doc = doc;
                    self.loading = false;
                    self.creating = false;

                };

                this.reader.readAsDataURL(file);
                this.loading = true;


            }

            ,


        }
    }


</script>

<style type="text/css" lang="scss">
    @import "~cropperjs/dist/cropper.css";

    $color: #6f42c1;
    .uploader-container {
        display: flex;
        justify-content: center;
        vertical-align: middle;
        align-items: center;
        text-align: right;
        /*min-height: 8rem;*/
        &:hover, &.hover {
            color: rgba($color, 20%);
            cursor: pointer;
        }

    }
</style>
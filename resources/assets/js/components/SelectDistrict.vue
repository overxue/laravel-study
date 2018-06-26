<template>
    <div class="form-group">
        <label class="control-label col-sm-2">省市区</label>
        <div class="col-sm-3">
            <select class="form-control" v-model="provinceId">
                <option value="">选择省</option>
                <option v-for="(name, id) in provinces" :value="id">{{ name }}</option>
            </select>
        </div>
        <div class="col-sm-3">
            <select class="form-control" v-model="cityId">
                <option value="">选择市</option>
                <option v-for="(name, id) in cities" :value="id">{{ name }}</option>
            </select>
        </div>
        <div class="col-sm-3">
            <select class="form-control" v-model="districtId">
                <option value="">选择区</option>
                <option v-for="(name, id) in districts" :value="id">{{ name }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    import addressData from 'china-area-data/v3/data'
    export default {
        data () {
            return {
                provinces: addressData['86'], // 省列表
                cities: {}, // 城市列表
                districts: {}, // 地区列表
                provinceId: '', // 当前选中的省
                cityId: '', // 当前选中的市
                districtId: '', // 当前选中的区
            }
        },
        watch: {
            provinceId (newVal) {
                this.cities = addressData[newVal]
            },
            cityId (newVal) {
                this.districts = addressData[newVal]
            }
        },
    }
</script>

<template>
    <div class="modal__temp_block">
        <div class="modal__temp_element">
            <img class="modal__temp_img" src="/icons/t1.png"/>
        </div>
        <div class="modal__temp_element">
            <a href="" @click.prevent="tempTenUp"><img class="modal__temp_img"
                                                       src="/icons/plus.png"/></a>
            <p>{{ tenTemp }}</p>
            <a href="" @click.prevent="tempTenDown"><img class="modal__temp_img"
                                                         src="/icons/minus.png"/></a>
        </div>
        <div class="modal__temp_element">
            <a href="" @click.prevent="tempOneUp"><img class="modal__temp_img"
                                                       src="/icons/plus.png"/></a>
            <p>{{ oneTemp }}</p>
            <a href="" @click.prevent="tempOneDown"><img class="modal__temp_img"
                                                         src="/icons/minus.png"/></a>
        </div>
        <div class="modal__temp_element">
            <a href="" @click.prevent="tempTenthUp"><img class="modal__temp_img"
                                                         src="/icons/plus.png"/></a>
            <p>.{{ tenthTemp }}</p>
            <a href="" @click.prevent="tempTenthDown"><img class="modal__temp_img"
                                                           src="/icons/minus.png"/></a>
        </div>
    </div>
</template>

<script>
export default {
    name: "select_temperature",
    props: ['tempSelectProps', 'nameSelectTempProps', 'nameValueProps'],
    data() {
        return {
            tempSelect: this.tempSelectProps,
            tenTemp: 0,
            oneTemp: 0,
            tenthTemp: 0,
        }
    },
    created() {
        this.changeTemp();
    },
    methods: {
        changeTemp() {
            let arrayTempSelect = String(this.tempSelect).split('');
            let numZero = 3 - arrayTempSelect.length;
            for (let i = 1; i <= numZero; i++) {
                arrayTempSelect.unshift('0');
            }
            this.tenTemp = arrayTempSelect[0];
            this.oneTemp = arrayTempSelect[1];
            this.tenthTemp = arrayTempSelect[2];
        },
        tempChange(operNumber, operation) {
            if (operation === 'up') {
                this.tempSelect = this.tempSelect + operNumber;
            } else {
                this.tempSelect = this.tempSelect - operNumber;
            }
            if (this.tempSelect > 995 || this.tempSelect < 5) {
                this.tempSelect = 0;
            }
            this.changeTemp();
            if (this.nameSelectTempProps === 'selectTempMain') {
                this.$eventBus.$emit('select_temp_main', this.tempSelect / 10);
            } else if (this.nameSelectTempProps === 'changeScheduleItem') {
                this.$eventBus.$emit('select_temp_mode', this.tempSelect / 10);
            }
        },
        tempTenUp() {
            this.tempChange(100, 'up');
        },
        tempTenDown() {
            this.tempChange(100, 'down');
        },
        tempOneUp() {
            this.tempChange(10, 'up');
        },
        tempOneDown() {
            this.tempChange(10, 'down');
        },
        tempTenthUp() {
            this.tempChange(5, 'up');
        },
        tempTenthDown() {
            this.tempChange(5, 'down');
        },
    },
}
</script>

<style scoped>

</style>

<template>
        <div v-if="educationLevel == 'allDegrees'">
            <div class="row justify-content-center">
                <span class="legend-mobile">
                    <div class="legend-green m-0">
                        <i class="fa fa-star" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">Graduate Degree</p>
                </span>
                <span class="legend-mobile">
                    <div class="legend-gold m-0">
                        <i class="fa fa-stop" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">Bachelor's Degree</p>
                </span>
                <span class="legend-mobile">
                    <div class="legend-oxford m-0">
                        <i class="fa fa-circle" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">Some College</p>
                </span>
            </div>  
        </div>
        <div v-else-if="educationLevel == 'someCollege'">
            <div class="row justify-content-center">
                <span class="legend-mobile">
                    <div class="legend-oxford-75 m-0">
                        <i class="fa fa-star" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">75th Percentile</p>
                </span>
                <span class="legend-mobile">
                    <div class="legend-oxford m-0">
                        <i class="fa fa-stop" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">50th Percentile</p>
                </span>
                <span class="legend-mobile">
                    <div class="legend-oxford-25 m-0">
                        <i class="fa fa-circle" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">25th Percentile</p>
                </span>
            </div>
        </div>
        <div v-else-if="educationLevel == 'bachelors'">
            <div class="row justify-content-center">
                <span class="legend-mobile">
                    <div class="legend-gold-75 m-0">
                        <i class="fa fa-star" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">75th Percentile</p>
                </span>
                <span class="legend-mobile">
                    <div class="legend-gold m-0">
                        <i class="fa fa-stop" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">50th Percentile</p>
                </span>
                <span class="legend-mobile">
                    <div class="legend-gold-25 m-0">
                        <i class="fa fa-circle" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">25th Percentile</p>
                </span>
            </div>
        </div>
        <div v-else-if="educationLevel == 'postBacc'">
            <div class="row justify-content-center">
                <span class="legend-mobile">
                    <div class="legend-green-75 m-0">
                        <i class="fa fa-star" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">75th Percentile</p>
                </span>
                <span class="legend-mobile">
                    <div class="legend-green m-0">
                        <i class="fa fa-stop" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">50th Percentile</p>
                </span>
                <span class="legend-mobile">
                    <div class="legend-green-25 m-0">
                        <i class="fa fa-circle" aria-hidden="true"></i>
                    </div><p class="ml-4 mt-1">25th Percentile</p>
                </span>
            </div>
        </div>
</template>
<script>
export default {
    props: ['educationLevel'],
    data() {
        return {
            isAll: true,
        }
    },
    computed: {
        windowSize() {
            return window.innerWidth;
        }
    }
}
</script>

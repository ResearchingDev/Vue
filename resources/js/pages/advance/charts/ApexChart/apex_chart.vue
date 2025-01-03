<template>
<Breadcrumbs main="Charts" title="ApexChart" />
<div class="container-fluid ">
    <div class="row">
        <barChart />
        <columnChart />
        <columnChart2 />
        <pieChart />
        <donutChart />
    </div>
</div>
</template>

<script>
import barChart from "./barChart.vue"
import columnChart from "./columnChart.vue"
import columnChart2 from "./columnChart2.vue"
import pieChart from "./pieChart.vue"
import donutChart from "./donutChart.vue"
export default {
    components: {
        barChart,
        columnChart,
        columnChart2,
        pieChart,
        donutChart,
    }
};
</script>

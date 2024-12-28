<template>
    <div>
        <Breadcrumbs main="Dashboard" title="default" />

        <div class="container-fluid">
            <div class="row widget-grid">
                <WelcomeCard />
                <!-- <PurchaseSaleCard />
                <OrdersProfitCard />
                <OverallBalanceCard />
                <RecentOrdersCard />
                <ActivitiesCard />
                <RecentSalesCard />
                <TimelineCard />
                <ProAccountCard /> -->
                <!-- <TotalUsersGrowthCard />
                <PaperNoteCard /> -->
            </div>
        </div>

    </div>
</template>

<script>
import WelcomeCard from "../dashboard/default/WelcomeCard.vue";
// import PurchaseSaleCard from "../dashboard/default/PurchaseSaleCard.vue";
// import OrdersProfitCard from "../dashboard/default/OrdersProfitCard.vue";
// import OverallBalanceCard from "../dashboard/default/OverallBalanceCard.vue";
// import RecentOrdersCard from "../dashboard/default/RecentOrdersCard.vue";
// import ActivitiesCard from "../dashboard/default/ActivitiesCard.vue";
// import RecentSalesCard from "../dashboard/default/RecentSalesCard.vue";
// import TimelineCard from "../dashboard/default/TimelineCard.vue";
// import ProAccountCard from "../dashboard/default/ProAccountCard.vue";
// import TotalUsersGrowthCard from "../dashboard/default/TotalUsersGrowthCard.vue";
// import PaperNoteCard from "../dashboard/default/PaperNoteCard.vue";

export default {
    components: {
        WelcomeCard,
        // PurchaseSaleCard,
        // OrdersProfitCard,
        // OverallBalanceCard,
        // RecentOrdersCard,
        // ActivitiesCard,
        // RecentSalesCard,
        // TimelineCard,
        // ProAccountCard,
        // TotalUsersGrowthCard,
        // PaperNoteCard,
    },
};
</script>

<template>
    <Head title="Staffs List" />
    <PageHeader :title="title" :items="items" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            
        </div>
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">
            <Lists />
        </div>
    </div>
</template>
<script>
import Lists from './Lists.vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components : { PageHeader, Lists },
    data() {
        return {
            currentUrl: window.location.origin,
            title: "List of Staffs",
            items: [{text: "Staff",href: "/"},{text: "Lists",active: true}],
        };
    },
}
</script>
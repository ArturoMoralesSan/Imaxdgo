<template>
    <a :href="$root.path + '/admin/pdf/' + branchid + 
        '?start_date=' + startdate + '&end_date=' + enddate">
        PDF
    </a>
</template>
<script>
    export default {
        props: {
            branchid: {
                type:Number,
                required: true
            },
            startdate: {
                type:String,
                required: true
            },
            enddate: {
                type:String,
                required: true
            },
        },
    };
</script>
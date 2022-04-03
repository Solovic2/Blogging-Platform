export default{
    data() {
        return {
            
        }
    },
    methods: {
        async callApi(way,url,data = ""){
            try {
                return  await axios({
                 method: way,
                 url: url,
                 data: data,
               });
            } catch (error) {
                return error.response
            }
        },
        info (nodesc,title,desc) {
            this.$Notice.info({
                title: title,
                desc: nodesc ? '' : desc
            });
        },
        success (nodesc,title,desc) {
            this.$Notice.success({
                title: title,
                desc: nodesc ? '' : desc
            });
        },
        warning (nodesc,title,desc){
            this.$Notice.warning({
                title: title,
                desc: nodesc ? '' : desc
            });
        },
        error (nodesc,title,desc) {
            this.$Notice.error({
                title: title,
                desc: nodesc ? '' : desc
            });
        }
    },
}
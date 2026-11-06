var geo_selec = new Vue({
    el: '#geo-selects',
    data: {
        geo1: [],
        geo2: [],
        region: '',
        comuna: ''
    },
    created: function(){
        //geo esta en otro archivo que depende de la localizacion de la session
        geo.map( (item) => {
            this.geo1.push(item.geo1);
            item.geo2.map( (item2) => {
                this.geo2.push(item2.nombre);
            });
        })
    },
    methods: {
        filterGeo2: filterGeo2
    }
});

function filterGeo2(event) {
    this.geo2 = [];
    this.communa = '';
    var selected = event.target.value;
    if (selected == '') {        
        return;
    }
    var filterGeo1 = geo.filter( (geoItem) => {
        return geoItem.geo1 == selected;
    });
    
    this.geo2 = filterGeo1[0].geo2.map( (geo2) => {
        return geo2.nombre;
    });
    this.geo2 = this.geo2.sort();
}

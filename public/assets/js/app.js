// Funciones globales (ej. poll realtime orders para admin)
(function(){
    if(window.location.search.indexOf('route=admin/dashboard') !== -1) {
        setInterval(fetchOrders, 5000);
    }
    function fetchOrders(){
        fetch(BASE_URL + '/?route=api/realtimeOrders')
            .then(r=>r.json())
            .then(data=>{
                // opcional: podrías refrescar una tabla o notificar nuevos pedidos
                console.log('Pedidos realtime', data);
            }).catch(e=>console.error(e));
    }
})();

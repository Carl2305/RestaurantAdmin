
/**
   * Easy selector helper function
   */
 const select = (el, all = false) => {
  el = el.trim()
  if (all) {
    return [...document.querySelectorAll(el)]
  } else {
    return document.querySelector(el)
  }
}

/**
   * Easy event listener function
   */
 const on = (type, el, listener, all = false) => {
  if (all) {
    select(el, all).forEach(e => e.addEventListener(type, listener))
  } else {
    select(el, all).addEventListener(type, listener)
  }
}

function isMobile(){
  return (
      (navigator.userAgent.match(/Android/i)) ||
      (navigator.userAgent.match(/webOS/i)) ||
      (navigator.userAgent.match(/iPhone/i)) ||
      (navigator.userAgent.match(/iPod/i)) ||
      (navigator.userAgent.match(/iPad/i)) ||
      (navigator.userAgent.match(/BlackBerry/i))
  );
}

// variables globales home
var dataTableHome;
var data_orders_all=[];
try {
  var modal_verify_pass=new bootstrap.Modal(select('#formvalidatepassword'),{});
} catch (e) {console.error(e.message);}

// variables globales sales
var data_sales_type=[];
var dataTableSale;

// variables globales orders
var dataTableOrders;
var data_orders_orders=[];

// variable global
try {
  var modal_detail_order = new bootstrap.Modal(select('#modal-detail-order'),{});
} catch (e) {console.error(e.message);}

/****
 * Funciones para la home
 */
const showDetailOrder = code => {
  try {
    let xhr=new XMLHttpRequest();
    xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/db-show-detail-order.php');
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
    xhr.onload = () => {
      let response=xhr.responseText;
      if(JSON.parse(response).length>0&&(response!=null||response!="")){
        let obj_head_order={};
        data_orders_all.forEach(function(item,index){
          if(item.id_order==code){
            obj_head_order={
              datetime_order: item.datetime_order,
              id_order: item.id_order,
              name_client: item.name_client,
              phone_client: item.phone_client,
              total_order: new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.total_order),
              address_client: item.address_client,
              reference_address_client: item.reference_address_client,
              status_order: item.status_order
            };
          }
        });
        let obj_body_order=JSON.parse(response);
        let codeHtmlModal="";
        obj_body_order.forEach(function(item,index){
          codeHtmlModal+=`<div class="col-12 row border border-primary mb-1">
          <div class="col-4">
            <div class="d-flex justify-content-center">
              <img src="${item.url_image_dish}" alt="coca cola 1.5Lt" class="w-50">
            </div>
          </div>
          <div class="col-8">
            <div class="col-12 d-flex justify-content-between">
              <p>Producto: <span>${item.name_dish}</span></p>
              <p>Precio: <span>${new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.price_dish)}</span></p>
            </div>
            <p>Descripción: <span>${item.description_dish}</span></p>
            <p>Comentario: <span>${item.comment_dish}</span></p>
          </div>
        </div>`;
        });

        
        modal_detail_order.show();

        select('#modal-detail-order h3 span').textContent=obj_head_order.id_order;
        select('#modal-detail-order p').textContent=obj_head_order.datetime_order;
        select('#modal-detail-order h2 span').textContent=obj_head_order.total_order;

        select('#modal-detail-order #order-name-client').textContent=obj_head_order.name_client;
        select('#modal-detail-order #order-phone-client').textContent=obj_head_order.phone_client;
        select('#modal-detail-order #order-address-client').textContent=obj_head_order.address_client;
        select('#modal-detail-order #order-reference-client').textContent=obj_head_order.reference_address_client;

        select('#modal-detail-order .modal-body #detail-order').innerHTML=codeHtmlModal;
        
        if(obj_head_order.status_order>0){
          select('#modal-detail-order .modal-footer #btn-check-order').classList.add('d-none');
        }else{
          select('#modal-detail-order .modal-footer #btn-check-order').classList.remove('d-none');
          select('#modal-detail-order #btn-check-order').dataset.ordercheck=obj_head_order.id_order;
        }
      }else{
        Swal.fire({
          title:'Error',
          text:'Este pedido no tiene platos solicitados',
          icon:'error'
        });
      }
    }
    xhr.send(`code_order=${code}`);
  } catch (e) {console.error(e.message);}
}

//var xhrmarkOrder;
const markOrder = (code, type) =>{
  try{
    select('#formvalidatepassword #txtpassword').value="";
    let textType="Enviada";
    (type=='check')? textType:textType="Cancelada";
    select('#formvalidatepassword .modal-body span').textContent=textType;
    modal_verify_pass.show();
    on('submit','#formvalidatepassword',function(e){ 
      e.preventDefault();
      let xhr=new XMLHttpRequest();
      xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/mark-check-order.php');
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.timeout=200;
      xhr.onload = () => {
        if(xhr.status===200){
          let response=xhr.responseText;
          console.log(response);
          if(response==0){
            Swal.fire({
              title:'Error',
              text:'',
              icon:'error'
            });
          }else if(response==1){
            Swal.fire({
              title:'Correcto',
              icon:'success',
              confirmButtonText: "OK",
              confirmButtonColor:'#0dcaf0',
              allowOutsideClick: false
            }).then((result)=>{
              if(result.isConfirmed){                
                loadDataServerPageHome();
                try { 
                  modal_verify_pass.hide(); 
                } catch(e){ console.error(e.message); }
                try { 
                  modal_detail_order.hide(); 
                } catch (e) { console.error(e.message); }
                xhr.abort();
                return;
              }
            });
          }else if(response==2){
            Swal.fire({
              title:'Error',
              text:'Contraseña Incorrecta',
              icon:'error'
            });
          }
        }else if(xhr.status===0){
          Swal.fire({
            title:'Correcto',
            icon:'success',
            confirmButtonText: "OK",
            confirmButtonColor:'#0dcaf0',
            allowOutsideClick: false
          }).then((result)=>{
            if(result.isConfirmed){
              xhr.abort();
              loadDataServerPageHome();
              try { 
                modal_verify_pass.hide(); 
              } catch(e){ console.error(e.message); }
              try { 
                modal_detail_order.hide(); 
              } catch (e) { console.error(e.message); }
            }
          });
          return;
        }
      }
      xhr.send(`type=${type}&pwd=${select('#formvalidatepassword #txtpassword').value}&code=${code}`);
    });
  }catch(e){console.error(e.message);}
}

const loadDataServerPageHome = () => {
  try{
    data_orders_all=[];
    let xhr=new XMLHttpRequest();
    xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/db-server-app.php');
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
    xhr.onload = () => {
      if(xhr.status===200){
        let response=xhr.responseText;
        if(JSON.parse(response).length>0&&(response!=null||response!="")){
          data_orders_all=JSON.parse(response);

          let total_sales=0;
          let total_sales_today=0.0;
          let dispatched_orders_today=0;
          let pending_orders_today=0;
          let cancel_orders_today=0;

          let arrayNew=[];
          
          data_orders_all.forEach(function(item,index){
            let status="";
            let buttons="";
            if(item.status_order==0){
              status='<p class="text-center"><span class="badge bg-warning">Pendiente</span></p>';
              buttons=`<div class="d-flex justify-content-center">
              <div class="btn-group" role="group" >
                <button class="btn btn-sm btn-primary" title="Ver detalle de la Orden" onclick="showDetailOrder(${item.id_order})"><i class="bi bi-eye"></i></button>`;
                if(isMobile()){
                  buttons+=`<a class="btn btn-sm btn-success" title="Llamar para Confirmar Orden" href="tel:+51${item.phone_client}"><i class="bi bi-telephone-outbound"></i></a>
                  <button class="btn btn-sm btn-secondary" title="Marcar como enviada" onclick="markOrder(${item.id_order},'check')"><i class="bi bi-check2-all"></i></button>
                  <button class="btn btn-sm btn-danger btn-cancel-order" title="Marcar como cancelado" onclick="markOrder(${item.id_order},'cancel')"><i class="bi bi-x-circle"></i></button> 
                  </div>
                </div>`;
                }else{
                  buttons+=`<button class="btn btn-sm btn-secondary" title="Marcar como enviada" onclick="markOrder(${item.id_order},'check')"><i class="bi bi-check2-all"></i></button>
                  <button class="btn btn-sm btn-danger btn-cancel-order" title="Marcar como cancelado" onclick="markOrder(${item.id_order},'cancel')"><i class="bi bi-x-circle"></i></button> 
                  </div>
                </div>`;
                }
                
              pending_orders_today++;
            }else if(item.status_order==1){
              status='<p class="text-center"><span class="badge bg-success">Enviado</span></p>';
              buttons=`<div class="d-flex justify-content-center">
              <div class="btn-group d-flex justify-content-center" role="group" >
                <button class="btn btn-sm btn-primary" title="Ver detalle de la Orden" onclick="showDetailOrder(${item.id_order})"><i class="bi bi-eye"></i></button>
              </div>
            </div>`;
              total_sales++;
              total_sales_today+=item.total_order;
              dispatched_orders_today++;
            }else{
              status='<p class="text-center"><span class="badge bg-danger">Cancelado</span></p>';
              buttons=`<div class="d-flex justify-content-center">
              <div class="btn-group d-flex justify-content-center" role="group" >
                <button class="btn btn-sm btn-primary" title="Ver detalle de la Orden" onclick="showDetailOrder(${item.id_order})"><i class="bi bi-eye"></i></button>
              </div>
            </div>`;
              cancel_orders_today++;
            }
            arrayNew.push([
              item.id_order,
              item.name_client,
              item.phone_client,
              item.datetime_order,
              //`<p class="text-end">${new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.total_order)}</p>`,
              `<p class="text-end">${item.total_order}0</p>`,
              status,
              buttons
            ]);

          });  

          try{
            dataTableHome.destroy();
          }catch{}

          dataTableHome=new simpleDatatables.DataTable('#datatable-home',{
            labels: {
              placeholder: "Buscar...",
              perPage: "Mostrar {select} registros por página", 
              noRows: "No hay datos", 
              noResults: "Ningún resultado coincide con su búsqueda", 
              info: "Mostrando {start} a {end} de {rows} registros" 
            },
            paging: true,
            perPageSelect: [5, 10, 15, 20, 25, 50, 100],
            data:{
              headings: ['#','Cliente','Telf. Cliente','Día y Hora Solicitado','Total S/','Estado','Opciones'],
              data: arrayNew,
            }
          });

          select('#number-sales-today').innerHTML=total_sales;
          select('#total-sales-today').innerHTML=new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(total_sales_today);
          
          select('#orders_today').innerHTML=data_orders_all.length;
          select('#dispatched_orders_today').innerHTML=dispatched_orders_today;
          select('#pending_orders_today').innerHTML=pending_orders_today;
          select('#cancel_orders_today').innerHTML=cancel_orders_today;

        }else{
          try{
            dataTableHome.destroy();
          }catch{}

          dataTableHome=new simpleDatatables.DataTable('#datatable-home',{
            labels: {
              placeholder: "Buscar...",
              perPage: "Mostrar {select} registros por página", 
              noRows: "No hay datos", 
              noResults: "Ningún resultado coincide con su búsqueda", 
              info: "Mostrando {start} a {end} de {rows} registros" 
            },
            paging: true,
            perPageSelect: [5, 10, 15, 20, 25, 50, 100],
            data:{
              headings: ['#','Cliente','Telf. Cliente','Día y Hora Solicitado','Total S/','Estado','Opciones'],
              data: [],
            }
          });

          select('#number-sales-today').innerHTML=0;
          select('#total-sales-today').innerHTML="S/ 0.00";
          
          select('#orders_today').innerHTML=0;
          select('#dispatched_orders_today').innerHTML=0;
          select('#pending_orders_today').innerHTML=0;
          select('#cancel_orders_today').innerHTML=0;
        }
      }
    }
    xhr.send('type=home&timer');
  }catch(e){console.error(e.message);}
}

/****
 * Fin Funciones para la home
 */


/****
 * Funciones para la ventas
 */
 const loadDataServerSale=(type="today")=>{
   try {
     let xhr=new XMLHttpRequest();
     xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/db-server-app.php');
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
     xhr.onload = () => {
       if(xhr.status===200){
         let response=xhr.responseText;
         if(JSON.parse(response).length>0){
           data_sales_type=JSON.parse(response);
           let total_sales_today=0.0;
           let arrayNew=[];
           data_sales_type.forEach(function(item,index){
             total_sales_today+=item.total_order;
             arrayNew.push([
               item.id_order,
               item.name_client,
               item.phone_client,
               item.datetime_order,
               //`<p class="text-end">${new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.total_order)}</p>`,
               `<p class="text-end">${item.total_order}0</p>`,
               `<div class="d-flex justify-content-center">
                   <div class="btn-group d-flex justify-content-center" role="group" >
                   <button class="btn btn-sm btn-primary" title="Ver detalle de la Venta" onclick="showDetailSale(${item.id_order})"><i class="bi bi-eye"></i></button>
                   </div>
               </div>`
             ]);
           });
           try{
             dataTableSale.destroy();
           }catch{}
           dataTableSale=new simpleDatatables.DataTable('#datatable-sale',{
             labels: {
               placeholder: "Buscar...",
               perPage: "Mostrar {select} registros por página", 
               noRows: "No hay datos", 
               noResults: "Ningún resultado coincide con su búsqueda", 
               info: "Mostrando {start} a {end} de {rows} registros" 
             },
               paging: true,
               perPageSelect: [5, 10, 15, 20, 25, 50, 100],
               data:{
                 headings: ['#','Cliente','Telf. Cliente','Día y Hora Solicitado','Total S/','Opciones'],
                 data: arrayNew,
               }
           });
           select('#number-sales-type').innerHTML=data_sales_type.length;
           select('#total-sales-type').innerHTML=new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(total_sales_today);     
         }else{
           try{
             dataTableSale.destroy();
           }catch{}
           dataTableSale=new simpleDatatables.DataTable('#datatable-sale',{
             labels: {
               placeholder: "Buscar...",
               perPage: "Mostrar {select} registros por página", 
               noRows: "No hay datos", 
               noResults: "Ningún resultado coincide con su búsqueda", 
               info: "Mostrando {start} a {end} de {rows} registros" 
             },
               paging: true,
               perPageSelect: [5, 10, 15, 20, 25, 50, 100],
               data:{
                 headings: ['#','Cliente','Telf. Cliente','Día y Hora Solicitado','Total S/','Opciones'],
                 data: [],
               }
           });
           select('#number-sales-type').innerHTML="0";
           select('#total-sales-type').innerHTML="S/ 0.00";  
         }
       }
     }
     xhr.send(`type=sale&timer=${type}`);
   } catch (e) {console.error(e.message);}
 }
 const showDetailSale=code=>{
   try {
     let xhr=new XMLHttpRequest();
     xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/db-show-detail-order.php');
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
     xhr.onload = () => {
      let response=xhr.responseText;
      if(JSON.parse(response).length>0){
        let obj_head_sale={};
        data_sales_type.forEach(function(item,index){
          if(item.id_order==code){
            obj_head_sale={
              datetime_order: item.datetime_order,
              id_order: item.id_order,
              name_client: item.name_client,
              phone_client: item.phone_client,
              total_order: new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.total_order),
              address_client: item.address_client,
              reference_address_client: item.reference_address_client,
              status_order: item.status_order
            };
          }
        });
        let obj_body_sale=JSON.parse(response);
        let codeHtmlModal="";
        obj_body_sale.forEach(function(item,index){
            codeHtmlModal+=`<div class="col-12 row border border-primary mb-1">
             <div class="col-4">
             <div class="d-flex justify-content-center">
                 <img src="${item.url_image_dish}" alt="coca cola 1.5Lt" class="w-50">
             </div>
             </div>
             <div class="col-8">
             <div class="col-12 d-flex justify-content-between">
                 <p>Producto: <span>${item.name_dish}</span></p>
                 <p>Precio: <span>${new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.price_dish)}</span></p>
             </div>
             <p>Descripción: <span>${item.description_dish}</span></p>
             <p>Comentario: <span>${item.comment_dish}</span></p>
             </div>
         </div>`;
        });
        modal_detail_order.show();
 
        select('#modal-detail-order h3 span').textContent=obj_head_sale.id_order;
        select('#modal-detail-order p').textContent=obj_head_sale.datetime_order;
        select('#modal-detail-order h2 span').textContent=obj_head_sale.total_order;
 
        select('#modal-detail-order #order-name-client').textContent=obj_head_sale.name_client;
        select('#modal-detail-order #order-phone-client').textContent=obj_head_sale.phone_client;
        select('#modal-detail-order #order-address-client').textContent=obj_head_sale.address_client;
        select('#modal-detail-order #order-reference-client').textContent=obj_head_sale.reference_address_client;
         
        select('#modal-detail-order .modal-body #detail-order').innerHTML=codeHtmlModal;
      }else{
        Swal.fire({
          title:'Error',
          text:'Este pedido no tiene platos solicitados',
          icon:'error'
        });
      }
     }
     xhr.send(`code_order=${code}`);
   } catch (e) {console.error(e.message);}
 }

 /****
 * Fin Funciones para la ventas
 */

/****
 * Funciones para Pedidos
 */
const loadDataServerPageOrders=(type="today")=>{
  try {
    let xhr=new XMLHttpRequest();
    xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/db-server-app.php');
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.onload=()=>{
      if(xhr.status===200){
        let response=xhr.responseText;
        if(JSON.parse(response).length>0){
          data_orders_orders=JSON.parse(response);
          let dispatched_orders=0;
          let pending_orders=0;
          let cancel_orders=0;
          let arrayNew=[];
          data_orders_orders.forEach(function(item,index){
            let status="";
            if(item.status_order==0){
              status='<p class="text-center"><span class="badge bg-warning">Pendiente</span></p>';
              pending_orders++;
            }else if(item.status_order==1){
              status='<p class="text-center"><span class="badge bg-success">Enviado</span></p>';
              dispatched_orders++;
            }else{
              status='<p class="text-center"><span class="badge bg-danger">Cancelado</span></p>';
              cancel_orders++;
            }
            arrayNew.push([
              item.id_order,
              item.name_client,
              item.phone_client,
              item.datetime_order,
              //`<p class="text-end">${new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.total_order)}</p>`,
              `<p class="text-end">${item.total_order}0</p>`,
              status,
              `<div class="d-flex justify-content-center">
                  <div class="btn-group d-flex justify-content-center" role="group" >
                  <button class="btn btn-sm btn-primary" title="Ver detalle de la Venta" onclick="showDetailOrderPage(${item.id_order})"><i class="bi bi-eye"></i></button>
                  </div>
              </div>`
            ]);
          });
          try{
            dataTableOrders.destroy();
          }catch{}
          dataTableOrders=new simpleDatatables.DataTable('#datatable-orders',{
            labels: {
              placeholder: "Buscar...",
              perPage: "Mostrar {select} registros por página", 
              noRows: "No hay datos", 
              noResults: "Ningún resultado coincide con su búsqueda", 
              info: "Mostrando {start} a {end} de {rows} registros" 
            },
            paging: true,
            perPageSelect: [5, 10, 15, 20, 25, 50, 100],
            data:{
              headings: ['#','Cliente','Telf. Cliente','Día y Hora Solicitado','Total S/','Estado','Opciones'],
              data: arrayNew,
            }
          });
          select('#lbltotalorders').innerHTML=data_orders_orders.length;
          select('#dispatched_orders_total').innerHTML=dispatched_orders;
          select('#pending_orders_total').innerHTML=pending_orders;
          select('#cancel_orders_total').innerHTML=cancel_orders;
        }else{
          try{
            dataTableOrders.destroy();
          }catch{}
          dataTableOrders=new simpleDatatables.DataTable('#datatable-orders',{
            labels: {
              placeholder: "Buscar...",
              perPage: "Mostrar {select} registros por página", 
              noRows: "No hay datos", 
              noResults: "Ningún resultado coincide con su búsqueda", 
              info: "Mostrando {start} a {end} de {rows} registros" 
            },
            paging: true,
            perPageSelect: [5, 10, 15, 20, 25, 50, 100],
            data:{
              headings: ['#','Cliente','Telf. Cliente','Día y Hora Solicitado','Total S/','Estado','Opciones'],
              data: [],
            }
          });
          select('#lbltotalorders').innerHTML=0;
          select('#dispatched_orders_total').innerHTML=0;
          select('#pending_orders_total').innerHTML=0;
          select('#cancel_orders_total').innerHTML=0;
        }
      }
    }
    xhr.send(`type=order&timer=${type}`);
  } catch (e) {console.error(e.message);}
}

const showDetailOrderPage=code=>{
  try {
    let xhr=new XMLHttpRequest();
    xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/db-show-detail-order.php');
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
    xhr.onload = () => {
      let response=xhr.responseText;
      if(JSON.parse(response).length>0){
        let obj_head_sale={};
        data_orders_orders.forEach(function(item,index){
          if(item.id_order==code){
            obj_head_sale={
              datetime_order: item.datetime_order,
              id_order: item.id_order,
              name_client: item.name_client,
              phone_client: item.phone_client,
              total_order: new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.total_order),
              address_client: item.address_client,
              reference_address_client: item.reference_address_client,
              status_order: item.status_order
            };
          }
        });
        let obj_body_sale=JSON.parse(response);
        let codeHtmlModal="";
        obj_body_sale.forEach(function(item,index){
          codeHtmlModal+=`<div class="col-12 row border border-primary mb-1">
            <div class="col-4">
            <div class="d-flex justify-content-center">
                <img src="${item.url_image_dish}" alt="coca cola 1.5Lt" class="w-50">
            </div>
            </div>
            <div class="col-8">
            <div class="col-12 d-flex justify-content-between">
                <p>Producto: <span>${item.name_dish}</span></p>
                <p>Precio: <span>${new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.price_dish)}</span></p>
            </div>
            <p>Descripción: <span>${item.description_dish}</span></p>
            <p>Comentario: <span>${item.comment_dish}</span></p>
            </div>
        </div>`;
        });
        modal_detail_order.show();
        select('#modal-detail-order h3 span').textContent=obj_head_sale.id_order;
        select('#modal-detail-order p').textContent=obj_head_sale.datetime_order;
        select('#modal-detail-order h2 span').textContent=obj_head_sale.total_order;
        select('#modal-detail-order #order-name-client').textContent=obj_head_sale.name_client;
        select('#modal-detail-order #order-phone-client').textContent=obj_head_sale.phone_client;
        select('#modal-detail-order #order-address-client').textContent=obj_head_sale.address_client;
        select('#modal-detail-order #order-reference-client').textContent=obj_head_sale.reference_address_client; 
        select('#modal-detail-order .modal-body #detail-order').innerHTML=codeHtmlModal;
      }else{
        Swal.fire({
          title:'Error',
          text:'Este pedido no tiene platos solicitados',
          icon:'error'
        });
      }
    }
    xhr.send(`code_order=${code}`);
  } catch (e) {console.error(e.message);}
}

/****
 * Fin Funciones para Pedidos
 */

(function() {
  "use strict";

  /**
   * Easy on scroll event listener 
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Sidebar toggle
   */
  if (select('.toggle-sidebar-btn')) {
    on('click', '.toggle-sidebar-btn', function(e) {
      select('body').classList.toggle('toggle-sidebar')
    })
  }

  /**
   * Search bar toggle
   */
  if (select('.search-bar-toggle')) {
    on('click', '.search-bar-toggle', function(e) {
      select('.search-bar').classList.toggle('search-bar-show')
    })
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select('#header')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
      } else {
        selectHeader.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }
  
  window.addEventListener('load',()=>{
    try {
      on('click','#close-session',function(e){
        e.preventDefault();
        let xhr=new XMLHttpRequest();
        xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/logueo.php');
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
        xhr.onload = () => {
          if(xhr.status===200){
            window.location.replace("http://localhost/RestaurantAdmin/");
          }
        }
        xhr.send('action=logout');
      });
    } catch (e) {console.error(e.message);}

    try {
      on('submit','#formUpdateUser',function(e){
        e.preventDefault();
        let xhr=new XMLHttpRequest();
        xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/db-server-app.php');
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
        xhr.onload = () => {
          if(xhr.status===200){
            let response=xhr.responseText;
            if(response!=""||response!=null){
              switch (parseInt(response)) {
                case 0: 
                  Swal.fire({
                    title: "Error",
                    text: "No se puedo actualizar tus datos, Intentalo más tarde",
                    icon: "error"
                  }); break;
                case 1: 
                  Swal.fire({
                    title: "Correcto",
                    text: "Datos Actualizados",
                    icon: "success",
                    confirmButtonText: "OK",
                    confirmButtonColor:'#0dcaf0',
                    allowOutsideClick: false
                  }).then((result)=>{
                    if(result.isConfirmed){
                      window.location.replace("http://localhost/RestaurantAdmin/profile.php");
                    }
                  }); break;
                default: 
                  Swal.fire({
                    title: "Error",
                    icon: "error"
                  }); break;
              }
            }
          }else{
            Swal.fire({
              title: "Error",
              icon: "error"
            });
          }
        }
        xhr.send(`type=update&firstName=${select('#formUpdateUser #firstName').value}&lastName=${select('#formUpdateUser #lastName').value}&email=${select('#formUpdateUser #email').value}`);
      });
    } catch (e) { console.error(e.message); }

    try {
      on('submit','#formUpdatePassUser',function(e){
        e.preventDefault();
        let xhr=new XMLHttpRequest();
        xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/db-server-app.php');
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
        xhr.onload = () => {
          if(xhr.status===200){
            let response=xhr.responseText;
            if(response!=""||response!=null){
              switch (parseInt(response)) {
                case 0: 
                  Swal.fire({
                    title: "Error",
                    text: "Ingrese su contraseña actual y la nueva",
                    icon: "error"
                  }); break;
                case 1: 
                  Swal.fire({
                    title: "Error",
                    text: "Tu contraseña actual es incorrecta.",
                    icon: "error"
                  }); break;
                case 2: 
                  Swal.fire({
                    title: "Error",
                    text: "La nueva contraseña y su confirmación no coinciden.",
                    icon: "error"
                  }); break;
                case 3: 
                  Swal.fire({
                    title: "Error",
                    text: "No se puedo actualizar tu contraseña, Intentalo más tarde",
                    icon: "error"
                  }); break;
                case 4:
                  Swal.fire({
                    title: "Correcto",
                    text: "Contraseña Actualizada",
                    icon: "success",
                    confirmButtonText: "OK",
                    confirmButtonColor:'#0dcaf0',
                    allowOutsideClick: false
                  }).then((result)=>{
                    if(result.isConfirmed){
                      window.location.replace("http://localhost/RestaurantAdmin/");
                    }
                  }); break;
                default: 
                  Swal.fire({
                    title: "Error",
                    icon: "error"
                  }); break;
              }
            }
          }else{
            Swal.fire({
              title: "Error",
              icon: "error"
            });
          }
        }
        xhr.send(`type=uptpass&pass=${select('#formUpdatePassUser #currentPassword').value}&newpass=${select('#formUpdatePassUser #newPassword').value}&renewpass=${select('#formUpdatePassUser #renewPassword').value}`);
      });
    } catch (e) { console.error(e.message); }

  });

})();
var data_users_all=[];
var dataTableMantUsers;
var data_dishes_all=[];
var dataTableMantDishes;
try {
  var modal_uptuser = new bootstrap.Modal(select('#formupdateuser'),{});
} catch (e) {console.error(e.message);}

try {
  var modal_detail_dish = new bootstrap.Modal(select('#modal-detail-dish'),{});
} catch (e) {console.error(e.message);}

try {
  var modal_uptdish = new bootstrap.Modal(select('#formupdatedish'),{});
} catch (e) {console.error(e.message);}

// employees
const disabledUser=(code,name)=>{
  Swal.fire({
    text:`¿Desea Deshabilitar el usuario de: ${name}?`,
    icon:'question',
    confirmButtonText: "OK",
    confirmButtonColor:'#0dcaf0',
    allowOutsideClick: false,
    showCancelButton: true,
    cancelButtonText: "Cancelar",
    cancelButtonColor:'#dc3545'
  }).then((result)=>{
    if(result.isConfirmed){
      try{
        let xhr=new XMLHttpRequest();
        xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/maintenance.php');
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
        xhr.onload = () => {
          if(xhr.status===200){
            let response=xhr.responseText;
            if(response!=""||response!=null){
              let text="error";
              let icon="error";
              let title="Error";
              switch (parseInt(response)) {
                case 0: text="Error, Intente más tarde"; break;
                case 1: text="Usted no tiene Cargo de Administrador de Sistema"; break;
                case 2: text="No se puedo Deshabilitar el usuario, Intente más tarde"; break;
                case 3: text="Usuario Deshabilitado"; icon="success"; title="Correcto"; break;
                default: text="Error"; break;
              }
              Swal.fire({
                title: title,
                text: text,
                icon: icon
              });
              loadDataServerManteUsers();
            }
          }
        }
        xhr.send(`type=delete&name&last&mail&user&npass&cpass&posit=${code}`);
      }catch(e){}
    }
  });
}

const loadDataServerManteUsers=()=>{
  try {
    data_users_all=[];
    let xhr=new XMLHttpRequest();
    xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/maintenance.php');
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
    xhr.onload = () => {
      if(xhr.status===200){
        let response=xhr.responseText;
        if(JSON.parse(response).length>0){
          data_users_all=JSON.parse(response);
          let arrayNew=[];
          let disabled_users_total=0;
          let enable_users_total=0;
          data_users_all.forEach(function(item,index){
            let status="";
            let buttons="";
            if(item.status_employee==0){
              disabled_users_total++;
              status='<p class="text-center"><span class="badge bg-danger">Deshabilitado</span></p>';
              buttons='<div class="d-flex justify-content-center"></div>';
            }else if(item.status_employee==1){
              enable_users_total++;
              status='<p class="text-center"><span class="badge bg-success">Habilitado</span></p>';
              buttons=`<div class="d-flex justify-content-center">
              <div class="btn-group d-flex justify-content-center" role="group" >
                <button class="btn btn-sm btn-warning" title="Actualizar Usuario" onclick="loadDataUser(${item.id_employee})"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger" title="Deshabiliatar Usuario" onclick="disabledUser(${item.id_employee},'${item.name_employee} ${item.lastname_employee}')"><i class="bi bi-trash"></i></button>
              </div>
            </div>`;
            }
            arrayNew.push([
              item.name_employee,
              item.lastname_employee,
              item.email_employee,
              item.name_position,
              status,
              buttons
            ]);
          });
          try{ dataTableMantUsers.destroy(); }catch{}
          try {
            dataTableMantUsers=new simpleDatatables.DataTable('#datatable-mant-users',{
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
                headings: ['Nombres','Apellidos','Correo Electrónico','Cargo','Estado','Opciones'],
                data: arrayNew,
              }
            });
          } catch (error) { }
          try {
            select('#number-users-total').innerHTML=data_users_all.length+1;
            select('#disabled_users_total').innerHTML=disabled_users_total;
            select('#enable_users_total').innerHTML=enable_users_total+1;
          } catch (error) { }
        }
      }
    }
    xhr.send(`type=list&name&last&mail&user&npass&cpass&posit`);
  } catch (e){console.error(e.message);}
}

const creteUsers=()=>{
  try {
    let xhr=new XMLHttpRequest();
    xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/maintenance.php');
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = () => {
      if(xhr.status===200){
        let response=xhr.responseText;
        if(response!=""||response!=null){
          let text="error";
          let icon="error";
          let title="Error";
          switch (parseInt(response)) {
            case 0: text="Error, Intente más tarde"; break;
            case 1: text="Usted no tiene Cargo de Administrador de Sistema"; break;
            case 2: text="Contraseñas Incorrectas"; break;
            case 3: text="No se puedo Registrar su cuenta, Intente más tarde"; break;
            case 4: text="Cuenta Registrada"; icon="success"; title="Correcto"; break;
            default: text="Error"; break;
          }
          Swal.fire({
            title: title,
            text: text,
            icon: icon
          });
          clearForm(); 
        }
      }else{
        console.error("error en la peticion");
        clearForm();
      }
    }
    xhr.send(`type=create&name=${select('#formcreateaccount #yourName').value}&last=${select('#formcreateaccount #lastName').value}&mail=${select('#formcreateaccount #yourEmail').value}&user=${select('#formcreateaccount #yourUsername').value}&npass=${select('#formcreateaccount #yourPassword').value}&cpass=${select('#formcreateaccount #yourConfirmPassword').value}&posit=${select('#formcreateaccount #yourPosition').value}`);
  } catch (e) {console.error(e.message);}
}

const loadDataUser=code=>{
  let xhr=new XMLHttpRequest();
    xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/db-server-app.php');
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
    xhr.onload = () => {
      let response=xhr.responseText;
      if(JSON.parse(response).length>0&&(response!=null||response!="")){
        let data_user=JSON.parse(response);
        modal_uptuser.show();
        select('#formupdateuser').dataset.usercode=code;
        select('#formupdateuser #name-user').innerHTML=`${data_user[0].name_employee} ${data_user[0].lastname_employee}`;
        select('#formupdateuser #yourName').value=data_user[0].name_employee;
        select('#formupdateuser #lastName').value=data_user[0].lastname_employee;
        select('#formupdateuser #yourEmail').value=data_user[0].email_employee;
        select('#formupdateuser #yourPosition').selectedIndex=parseInt(data_user[0].id_position)-1;
      }
    }
    xhr.send(`type=search&timer=${code}`);
}

const updateUser=code=>{
  try {
    let xhr=new XMLHttpRequest();
    xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/maintenance.php');
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = () => {
      if(xhr.status===200){
        let response=xhr.responseText;
        if(response!=""||response!=null){
          let text="error";
          let icon="error";
          let title="Error";
          switch (parseInt(response)) {
            case 0: text="Error, Intente más tarde"; break;
            case 1: text="Usted no tiene Cargo de Administrador de Sistema"; break;
            case 2: text="No se puedo actualizar el usuario, Intente más tarde"; break;
            case 3: text="Usuario Actualizado"; icon="success"; title="Correcto"; break;
            default: text="Error"; break;
          }
          Swal.fire({
            title: title,
            text: text,
            icon: icon
          });
          clearFormUpdate(); 
          modal_uptuser.hide();
          loadDataServerManteUsers();
        }
      }else{
        console.error("error en la peticion");
        clearFormUpdate();
      }
    }
    xhr.send(`type=update&name=${select('#formupdateuser #yourName').value}&last=${select('#formupdateuser #lastName').value}&mail=${select('#formupdateuser #yourEmail').value}&user=${code}&npass&cpass&posit=${select('#formupdateuser #yourPosition').value}`);
  } catch (e) {console.error(e.message);}
}

const clearForm=()=>{
  select('#formcreateaccount #yourName').value="";
  select('#formcreateaccount #lastName').value="";
  select('#formcreateaccount #yourEmail').value="";
  select('#formcreateaccount #yourPosition').selectedIndex=0;
  select('#formcreateaccount #yourUsername').value="";
  select('#formcreateaccount #yourPassword').value="";
  select('#formcreateaccount #yourConfirmPassword').value="";
}

const clearFormUpdate=()=>{
  select('#formupdateuser #name-user').innerHTML="";
  select('#formupdateuser #yourName').value="";
  select('#formupdateuser #lastName').value="";
  select('#formupdateuser #yourEmail').value="";
  select('#formupdateuser #yourPosition').selectedIndex=0;
  select('#formupdateuser #yourPosition').dataset.usercode=0;
}

// dishes
const loadDataDishes=()=>{
  data_dishes_all=[];
  let xhr=new XMLHttpRequest();
  xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/maintenance.php');
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
  xhr.onload = () => {
    if(xhr.status===200){
      let response=xhr.responseText;
      if(JSON.parse(response).length>0){
        data_dishes_all=JSON.parse(response);
        let arrayNew=[];
        let dishes_total=0;
        let drinks_total=0;
        let salads_total=0;
        let combos_total=0;
        data_dishes_all.forEach(function(item,index){
          let status="";
          let buttons="";
          switch (parseInt(item.id_category)) {
            case 1: dishes_total++; break;
            case 2: salads_total++; break;
            case 3: drinks_total++; break;
            case 4: combos_total++; break;
          }
          if(item.status_dish==0){
            status='<p class="text-center"><span class="badge bg-danger">Deshabilitado</span></p>';
            buttons=`<div class="d-flex justify-content-center"><button class="btn btn-sm btn-info" title="Ver Platillo" onclick="showDetailDish('${item.code_dish}',${item.id_dish})"><i class="bi bi-eye"></i></button></div>`;
          }else if(item.status_dish==1){
            status='<p class="text-center"><span class="badge bg-success">Habilitado</span></p>';
            buttons=`<div class="d-flex justify-content-center">
            <div class="btn-group d-flex justify-content-center" role="group" >
              <button class="btn btn-sm btn-info" title="Ver Platillo" onclick="showDetailDish('${item.code_dish}',${item.id_dish})"><i class="bi bi-eye"></i></button>
              <button class="btn btn-sm btn-warning" title="Actualizar Platillo" onclick="loadDataDishUpdate('${item.code_dish}',${item.id_dish})"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-danger" title="Deshabiliatar Platillo" onclick="disableDish('${item.code_dish}','${item.name_dish}',${item.id_dish})"><i class="bi bi-trash"></i></button>
            </div>
          </div>`;
          }
          arrayNew.push([
            item.id_dish,
            item.name_dish,
            item.description_dish.substring(0, 35),
            item.name_category,
            `<p class="text-end">${new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.price_dish)}</p>`,
            //`<p class="text-end">${item.total_order}0</p>`,
            status,
            buttons
          ]);
        });
        try{ dataTableMantDishes.destroy(); }catch{}
        try {
          dataTableMantDishes=new simpleDatatables.DataTable('#datatable-mant-dishes',{
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
              headings: [' # ','Nombre','Descripción','Categoria','Precio','Estado','Opciones'],
              data: arrayNew,
            }
          });
        } catch (error) {}
        try{
          select('#number_dishes_total').innerHTML=data_dishes_all.length;
          select('#dishes_total').innerHTML=dishes_total;
          select('#drinks_total').innerHTML=drinks_total;
          select('#salads_total').innerHTML=salads_total;
          select('#combos_total').innerHTML=combos_total;
        }catch{}
      }
    }
  }
  xhr.send(`type=listdish`);
}

const showDetailDish=(code,id)=>{
  data_dishes_all.forEach(function (item,index) {
    if(item.id_dish==id && item.code_dish==code){
      modal_detail_dish.show();
      select('#modal-detail-dish h3 #number_dish').textContent=id;
      select('#modal-detail-dish h2 #price_dish').textContent=new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.price_dish);
      select('#modal-detail-dish img').setAttribute('src', item.url_image_dish);
      select('#modal-detail-dish img').setAttribute('alt', item.name_dish);
      select('#modal-detail-dish .modal-body #name_dish').textContent=item.name_dish;
      select('#modal-detail-dish .modal-body #descrip_dish').textContent=item.description_dish;
      select('#modal-detail-dish .modal-body #name_categ').textContent=item.name_category;
   }
  });
}

const disableDish=(code,name,id)=>{
  Swal.fire({
    text:`¿Desea Deshabilitar el Platillo de: ${name}?`,
    icon:'question',
    confirmButtonText: "OK",
    confirmButtonColor:'#0dcaf0',
    allowOutsideClick: false,
    showCancelButton: true,
    cancelButtonText: "Cancelar",
    cancelButtonColor:'#dc3545'
  }).then((result)=>{
    if(result.isConfirmed){
      try{
        let xhr=new XMLHttpRequest();
        xhr.open('POST','http://localhost/RestaurantAdmin/assets/vendor/access-data/maintenance.php');
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
        xhr.onload = () => {
          if(xhr.status===200){
            let response=xhr.responseText;
            if(response!=""||response!=null){
              let text="error";
              let icon="error";
              let title="Error";
              switch (parseInt(response)) {
                case 0: text="Error, Intente más tarde"; break;
                case 1: text="Usted no tiene Cargo de Administrador de Sistema"; break;
                case 2: text="No se puedo Deshabilitar el Platillo, Intente más tarde"; break;
                case 3: text="Platillo Deshabilitado"; icon="success"; title="Correcto"; break;
                default: text="Error"; break;
              }
              Swal.fire({
                title: title,
                text: text,
                icon: icon
              });
              loadDataDishes();
            }
          }
        }
        xhr.send(`type=deletedish&code=${code}&dish=${id}`);
      }catch(e){}
    }
  });
}

const loadDataDishUpdate=(code,id)=>{
  data_dishes_all.forEach(function (item,index) {
    if(item.id_dish==id && item.code_dish==code){
      modal_uptdish.show();
      select('#formupdatedish h3 #name_dish').textContent=item.name_dish;
      select('#formupdatedish img').setAttribute('src', item.url_image_dish);
      select('#formupdatedish img').setAttribute('alt', item.name_dish);
      select('#formupdatedish #namedish').value=item.name_dish;
      select('#formupdatedish #pricedish').value=item.price_dish;
      select('#formupdatedish .modal-body #namedish').value=item.name_dish;
      select('#formupdatedish .modal-body #descripdish').value=item.description_dish;
      select('#formupdatedish .modal-body #category').selectedIndex=parseInt(item.id_category)-1;
      select('#formupdatedish #btnsendupdatedish').dataset.dish=item.id_dish;
      select('#formupdatedish #btnsendupdatedish').dataset.codedish=item.code_dish;
    }
  });
}

const updateDish=(action,formData,thisForm)=>{
  fetch(`${action}access-data/maintenance.php`, {
    method: 'POST',
    body: formData,
    //headers: {'Content-Type': 'multipart/form-data'}
    headers: {'X-Requested-With': 'XMLHttpRequest'}
  })
  .then(response => {
    if( response.ok ) {
      return response.text();
    } else {
      throw new Error(`${response.status} ${response.statusText} ${response.url}`); 
    }
  })
  .then(data => {
    if (data.trim() == 'OK') {
      thisForm.reset(); 
      modal_uptdish.hide();
      Swal.fire({
        title:'Correcto',
        text:'Platillo Actualizado',
        icon:'success',
        confirmButtonText: "OK",
        confirmButtonColor:'#0dcaf0'
      });
      loadDataDishes();
    } else {
      Swal.fire({
        title:'Error',
        text: data.trim(),
        icon:'error',
        confirmButtonText: "OK",
        confirmButtonColor:'#0dcaf0'
      });
    }
  })
  .catch((error) => {
    console.error(error.message);
  });
}

const createDish=(action,formData,thisForm)=>{
  fetch(`${action}access-data/maintenance.php`, {
    method: 'POST',
    body: formData,
    //headers: {'Content-Type': 'multipart/form-data'}
    headers: {'X-Requested-With': 'XMLHttpRequest'}
  })
  .then(response => {
    if( response.ok ) {
      return response.text();
    } else {
      throw new Error(`${response.status} ${response.statusText} ${response.url}`); 
    }
  })
  .then(data => {
    if (data.trim() == 'OK') {
      thisForm.reset(); 
      Swal.fire({
        title:'Correcto',
        text:'Platillo Registrado',
        icon:'success',
        confirmButtonText: "OK",
        confirmButtonColor:'#0dcaf0'
      });
      loadDataDishes();
    } else {
      Swal.fire({
        title:'Error',
        text: data.trim(),
        icon:'error',
        confirmButtonText: "OK",
        confirmButtonColor:'#0dcaf0'
      });
    }
  })
  .catch((error) => {
    console.error(error.message);
  });
}


window.addEventListener('load',()=>{
  
  // employees
  try {
    loadDataServerManteUsers();
  } catch (e) {console.error(e.message);}
  
  try {
    on('submit','#formcreateaccount',function(e){
      e.preventDefault();
      if(select('#yourPassword').value===select('#yourConfirmPassword').value){
        creteUsers();
      }else{
        Swal.fire({
          title:'Error',
          text:'Contraseñas Incorrectas',
          icon:'error'
        });
        select('#yourConfirmPassword').value="";
      }
    });
  } catch (e) {console.error(e.message);}
  
  try {
    on('click','#btnreloaddatausers',function(e){
      e.preventDefault();
      loadDataServerManteUsers();
    });
  } catch (e) {console.error(e.message);}

  try {
    on('click','#btntablistusers',function(e){
      e.preventDefault();
      loadDataServerManteUsers();
    });
  } catch (e) {console.error(e.message);}
  
  try {
    on('submit','#formupdateuser',function(e){
      e.preventDefault();
      updateUser(this.dataset.usercode);
    });
  } catch (e) {console.error(e.message);}

  // End Employees

  // Dishes
  
  try {    
    loadDataDishes();
  } catch (e) {console.error(e.message);}

  try {
    on('click','#btntablistdishes',function(e){
      e.preventDefault();
      loadDataDishes();
    });
  } catch (e) {console.error(e.message);}

  try {
    on('click','#btnreloaddatadishes',function(e){
      e.preventDefault();
      loadDataDishes();
    });
  } catch (e) {console.error(e.message);}

  try {
    on('submit','#formupdatedish',function (e) {
      e.preventDefault();
      let thisForm=this;
      let action=thisForm.getAttribute('action');
      let formData=new FormData(thisForm);
      if(select('#formupdatedish #uploadimage').files.length>0){
        formData.append('imgpost','true');
      }else{
        formData.append('imgpost','false');
      }
      formData.append('type','updatedish');
      formData.append('dish',select('#formupdatedish #btnsendupdatedish').dataset.dish);
      formData.append('dishcode',select('#formupdatedish #btnsendupdatedish').dataset.codedish);
      updateDish(action,formData,thisForm);
    });
  }  catch (e) {console.error(e.message); }

  try {
    on('submit','#formcreatedish',function (e){
      e.preventDefault();
      let thisForm=this;
      let action=thisForm.getAttribute('action');
      let formData=new FormData(thisForm);
      formData.append('type','createdish');
      createDish(action,formData,thisForm);
    });
  } catch (e) {console.error(e.message); }

  // End Dishes
});
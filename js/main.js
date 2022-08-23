var saveUser;

new Vue({
	el: "#all-users",
	data: {
		depNewUser: '',
		visAddUser: false,
	},
	methods: {
		getLengthUsersCompany(id_company){
			var count;
			$.ajax({
				async: false,
				url: '/ajax/getLengthUsersCompany.php',
				type: 'POST',
				dataType: 'json',
				data: {id_company: id_company},
			}).always(function(data) {
				count = data;
			});

			return count;
		},

		delUser(user_id){
			$.ajax({
				url: '/ajax/delUser.php',
				type: 'POST',
				dataType: 'json',
				data: {user_id: user_id},
			}).always(function(data) {
				if (data.status) {
					location.reload();
				}else{
					alert(data.message);
				}
			});
			
		},

		delAllUsers(company_id){
			var permission_to_delete = confirm("Вы действительно хотите удалить всех пользователей?");
			if(permission_to_delete){
				$.ajax({
					url: '/ajax/delAllUsers.php',
					type: 'POST',
					dataType: 'json',
					data: {company_id: company_id},
				}).always(function(data) {
					if (data.status) {
						location.reload();
					}else{
						alert(data.message);
					}
				});
			}
			
		},

		addUser(fio = this.fioNewUser, department = this.depNewUser){
			$.ajax({
				url: '/ajax/addUser.php',
				type: 'POST',
				dataType: 'json',
				data: {fio: fio, department: department},
			}).always(function(data) {
				if (data.status) {
					location.reload();
				}else{
					alert(data.message);
				}
			});
			
		},

		startDrag: (evt, item) => {
			saveUser = item;
		},

		getRegister(user_id){
			var date='';
			$.ajax({
				async: false,
				url: '/ajax/getRegister.php',
				type: 'POST',
				dataType: 'json',
				data: {user_id: user_id},
			}).always(function(date_inf) {
				date = date_inf;
			});
			return date;
			
		},

		getLengthUsers(idDep){
			var res='';
			$.ajax({
				async: false,
				url: '/ajax/getLengthUsers.php',
				type: 'POST',
				dataType: 'json',
				data: {id_department: idDep},
			}).always(function(data) {
				res = data;
			});
			return res;
		},

		getDepartment(idCompany){
			var res='';
			$.ajax({
				async: false,
				url: '/ajax/getDepartment.php',
				type: 'POST',
				dataType: 'json',
				data: {id_company: idCompany},
			}).always(function(data) {
				res = data;
			});
			return res;
		},

		getUserInDepartment(idDep){
			var res='';
			$.ajax({
				async: false,
				url: '/ajax/getUserInDepartment.php',
				type: 'POST',
				dataType: 'json',
				data: {id_department: idDep},
			}).always(function(data) {
				res = data;
			});
			return res;
		},

		hideShowDepartmentUsers(idDep){
			var buttonClick = document.querySelector("label.dep-arrow[department_id='"+idDep+"']");
			var elemDepartment = document.querySelector(".department[department_id='"+idDep+"']");
			var allUsersDepartment = elemDepartment.querySelectorAll(".department_user");

			if (buttonClick.classList.contains('active')) {
				for (var i = 0; i < allUsersDepartment.length; i++) {
					allUsersDepartment[i].style.display = 'none';
				}
			}else{
				for (var i = 0; i < allUsersDepartment.length; i++) {
					allUsersDepartment[i].style.display = 'grid';
				}
			}
			buttonClick.classList.toggle("active");
		}
	}
});

new Vue({
	el: "#calendar",
	data: {
		selectMounth: new Date().getMonth(),
		selectYear: new Date().getFullYear(),
		monthes: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
		DaysOfWeek : ['Пн','Вт','Ср','Чт','Пт','Сб','Вс'],
		recordUsers : [],
	},
	methods: {
		drop (date) {
	        var recordInfo = {user_id: saveUser.id, date: date}
	        this.recUser(recordInfo);
	    },

	    recUser(recordInfo){
	    	for (let i = this.recordUsers.length; i--; ) {
			  if (this.recordUsers[i].user_id === recordInfo.user_id) {
			    this.recordUsers.splice(i, 1);
			  }
			}
			this.recordUsers.push(recordInfo);
			setTimeout(() => this.showLocalRec(), 50);
	    },

	    dragover (event) {
	        event.preventDefault()
		},

		getCountRegister(){
			var count;
			$.ajax({
				async: false,
				url: '/ajax/getCountReg.php',
				type: 'POST',
				dataType: 'json',
				data: {},
			}).always(function(date_ret) {
				count = date_ret;
			});
			return count;
		},

		getRegisterDay(date){
			var ret;
			$.ajax({
				async: false,
				url: '/ajax/getRegisterDay.php',
				type: 'POST',
				dataType: 'json',
				data: {date: date},
			}).always(function(date_ret) {
				ret = date_ret;
			});
			return ret;
		},

		addRecUsers(){
			var ret;
			$.ajax({
				async: false,
				url: '/ajax/addRecUsers.php',
				type: 'POST',
				dataType: 'json',
				data: {data_rec: this.recordUsers},
			}).always(function(date) {
				ret = date;
			});
			if (ret) {
				location.reload();
			}
		},

		showLocalRec(){
			let elements = document.querySelectorAll('td.normal');
			for (let elem of elements) {
				elem.style.background = '#ffffff';
			}
			for (let i = this.recordUsers.length; i--; ) {
				var check_date = this.recordUsers[i].date.split('-');
				var el = document.querySelector("td.normal[year='"+check_date[0]+"'][month='"+check_date[1]+"'][day='"+check_date[2]+"']")
				if (el) {
					el.style.background = '#e32e4336';
				}
			}
		},

		getMonthes(){
			var nowDate = new Date();
			var monthes = [];
			for(var i=0; i < 8 ;i++) {
				if ((nowDate.getMonth() + i) % 12 + 1 < nowDate.getMonth()) {
					var dateM = {month: (nowDate.getMonth() + i) % 12 + 1, year: nowDate.getFullYear()+1};
				}else{
					var dateM = {month: (nowDate.getMonth() + i) % 12 + 1, year: nowDate.getFullYear()};
				}
				monthes.push(dateM);
			}
			return monthes;
		},

		checkSame($el){
			if ($el == this.selectMounth) {
				return true;
			}else{
				return false;
			}
		},

		getLengthUsersCompany(id_company){
			var count;
			$.ajax({
				async: false,
				url: '/ajax/getLengthUsersCompany.php',
				type: 'POST',
				dataType: 'json',
				data: {id_company: id_company},
			}).always(function(data) {
				count = data;
			});

			return count;
		},

		getCalendar(){
			var days = [];
			var d = new Date();
			var y = this.selectYear;
			var m = this.selectMounth;
			var firstDayOfMonth = new Date(y, m, 7).getDay();
			var lastDateOfMonth =  new Date(y, m+1, 0).getDate();
			var lastDayOfLastMonth = m == 0 ? new Date(y-1, 11, 0).getDate() : new Date(y, m, 0).getDate();

			  var i=1;
			  do {
			    var dow = new Date(y, m, i).getDay();
			    if ( dow == 1 ) {
			    }else if ( i == 1 ) {
			      var k = lastDayOfLastMonth - firstDayOfMonth+1;
			      for(var j=0; j < firstDayOfMonth; j++) {
			        var day = {num_day: k, month: m+1, year: y, check_class:false}
			        days.push(day);
			        k++;
			      }
			    }
			    var chk = new Date();
			    var chkY = chk.getFullYear();
			    var chkM = chk.getMonth();
			    const firstDate = new Date(chkY+'-'+chkM+'-'+chk.getDate());
				const secondDate = new Date(this.selectYear+'-'+this.selectMounth+'-'+i);
			    if (firstDate >= secondDate) {
			      	 var day = {num_day: i, month: m+1, year: y, check_class:false}
			        days.push(day);
			    } else {
			    	var day = {num_day: i, month: m+1, year: y, check_class:true}
			        days.push(day);
			    }
			    if ( dow == 0 ) {
			    }else if ( i == lastDateOfMonth ) {
			      var k=1;
			      for(dow; dow < 7; dow++) {
			        var day = {num_day: k, month: m+1, year: y, check_class:false}
			        days.push(day);
			        k++;
			      }
			    }
			    i++;
			  }while(i <= lastDateOfMonth);
			  return this.sliceIntoChunks(days,7);
		},

		sliceIntoChunks(arr, chunkSize) {
		    const res = [];
		    for (let i = 0; i < arr.length; i += chunkSize) {
		        const chunk = arr.slice(i, i + chunkSize);
		        res.push(chunk);
		    }
		    return res;
		},

		editCalendar(selMounth, selYear){
			this.selectMounth = selMounth;
			this.selectYear = selYear;
			this.getCalendar();
			setTimeout(() => this.showLocalRec(), 50);
		},
		
	},
	created: function(){
    	this.getCalendar();
    	setTimeout(() => this.showLocalRec(), 50);
    }

});

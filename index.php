<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Тестовое задание Insp</title>
	<link rel="stylesheet" type="text/css" href="/css/fonts.css">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<script src="/js/vue.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<section id="insp">
	<div class="left-section"><img src="img/Logo.png" alt="Logo Insp"></div>
	<div class="right-section">
		<div class="top-menu" id="top-menu">
			<div class="top-menu_back">
				<img src="img/arrow-left.png" alt="arrow-left">
				<span>1205-20 от 01.01.2020</span>
			</div>
			<div class="top-menu_name-company">
				<img src="img/logo-company.png" alt="ООО «Технониколь»">
				<span>ООО «Технониколь»</span>
			</div>
			<div class="top-menu_shop-info">
				<img src="img/shop.png" alt="shop">
				<span>4</span>
			</div>
		</div>
		<div class="main-section">
			<div class="main-section_left" id="calendar">
				<h1 class="main-section_title">Предварительный медицинский осмотр лиц, поступающих в учебные заведения гражданской авиации (ГА)</h1>

				<div class="calendar" >
					<div class="all-month">
						<div class="all-month_item" v-for="getMonth in getMonthes()" v-bind:class="{active : checkSame(getMonth.month - 1)}" @click="editCalendar(getMonth.month-1, getMonth.year)">{{monthes[getMonth.month-1]}} </div>
					</div>
					<div class="free-seats" @click="getCalendar()"><p>Свободных мест <span>{{1000 - getCountRegister()}}</span> из 1000</p></div>
					<div class="main-calendar" id="main-calendar">
						<table>
							<thead>
								<tr class="days">
									<th v-for="DayOfWeek in DaysOfWeek">{{DayOfWeek}}</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="week in getCalendar()">
									<td v-for="day in week" v-bind:year="day.year" v-bind:month="day.month" v-bind:day="day.num_day" v-bind:class="[day.check_class ? 'normal' : 'not-current']" >
										<div v-if="day.check_class" @drop="drop(day.year+'-'+day.month+'-'+day.num_day)" @dragover='dragover'>
											{{day.num_day}}
											<div class="available">{{ 50 - (getRegisterDay(day.year+'-'+day.month+'-'+day.num_day))}} из 50</div>
										</div>
										<div v-else>
											{{day.num_day}}
											<div class="available">&#8291;</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="function-bar">
						<div class="info">
							<div class="info_item">
								<p class="info_item_title">Мест по договору</p>
								<p class="info_item_num">100</p>
							</div>
							<div class="info_item">
								<p class="info_item_title">Записанных</p>
								<p class="info_item_num">{{getLengthUsersCompany(1).rec_users}}</p>
							</div>
							<div class="info_item">
								<p class="info_item_title">Свободных</p>
								<p class="info_item_num">{{100 - getLengthUsersCompany(1).rec_users}}</p>
							</div>
						</div>
						<div class="buttons">
							<div class="distribute_auto">Распределить автоматически <span>{{getLengthUsersCompany(1).all_users - getLengthUsersCompany(1).rec_users}}</span></div>
							<div class="save" @click="addRecUsers()">Записать</div>
						</div>
					</div>
				</div>
			</div>
			<div class="main-section_right">
				<div class="all-users" id="all-users">
					<div class="button-bar_edit-user">
						<button class="add-user" v-on:click="visAddUser=!visAddUser">{{visAddUser?'Отменить':'Добавить сотрудников'}}</button>
						<button class="del-all-users" v-on:click=delAllUsers(1)><img src="img/del.png" alt="del"><span>Удалить всех</span></button>
					</div>
					<div class="add-new-user" v-if="visAddUser">
						<input type="text"placeholder="ФИО" v-model="fioNewUser">
						<select v-model="depNewUser">
							<option disabled value="">Выберите отдел</option>
							<option v-for="dep in getDepartment(1)" v-bind:id="dep.id" v-bind:value = "dep.id">{{dep.name}}</option>
						</select>
						<button class="add-user" @click="addUser()">Добавить</button>
					</div>
					<div class="all-users_title">
						<h2>Список сотрудников <span>{{getLengthUsersCompany(1).all_users}}</span></h2>
						<p>Записанных <span>{{getLengthUsersCompany(1).rec_users}}</span></p>
					</div>
					<div class="all-users_table">
						<div class="all-users_table_head">
							<div>ФИО</div>
							<div>Дата записи</div>
						</div>
						<div class="all-users_table_items">
							<div class="department" v-for="dep in getDepartment(1)" v-bind:department_id="dep.id">
								<div class="department-name" :key="dep.name"><label><img src="img/moving.png" alt="moving"></label><p>{{dep.name}}</p> <span>{{getLengthUsers(dep.id)}}</span> <label class="dep-arrow active" v-bind:department_id="dep.id" v-on:click="hideShowDepartmentUsers(dep.id)"><img src="img/arrow-bottom.png" alt="arrow-bottom"></label></div>
								<div class="department_user" v-for="user in getUserInDepartment(dep.id)" :key='user.id' draggable
        @dragstart='startDrag($event, user)'><label><img src="img/moving.png" alt="moving"></label><div class="name">{{user.fio}}</div> <div class="date">{{getRegister(user.id)}}</div><div class="func-del" @click="delUser(user.id)"><img src="img/del.png" alt="del"></div></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script src="/js/main.js"></script>
</body>
</html>
@extends('skeleton::layout')
@section("title","Grades")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Grade Details</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('hr/grades') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request('search') }}" placeholder="Search">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm white m-b" type="submit">Search</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12 col-md-5">
                        <div class="box form-colors" id="gradeForm">
                            <div class="box-header">
                                <form @submit.prevent="submitGradeForm">
                                    <div class="form-group">
                                        <label for="name">Grade Name</label>
                                        <input class="form-control form-control-sm" id="name" name="name"
                                               v-model="form.name"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="name_bn">গ্রেড (বাংলায়)</label>
                                        <input class="form-control form-control-sm" name="name_bn" id="name_bn"
                                               v-model="form.name_bn"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="group_id">Group</label>
                                        <select class='form-control' v-model="form.group_id">
                                            <option v-for="(data, index) in groups" :value="data.id"
                                                    v-text="data.name"></option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success">
                                            <em class="fa fa-save"></em>
                                            <span v-text="form.id ? 'Update' : 'Create'"></span>
                                        </button>
                                        <a href="/hr/grades" class="btn btn-sm btn-warning">
                                            <em class="fa fa-refresh"></em> Refresh</a>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>নাম (বাংলায়)</th>
                                <th>Group</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($grades as $grade)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $grade->id }}</td>
                                    <td>{{ $grade->name }}</td>
                                    <td>{{ $grade->name_bn }}</td>
                                    <td>{{ $grade->group->name }}</td>
                                    <td>
                                        <button class="btn btn-xs btn-success"
                                                onclick="editGrade({{ $grade->id }})">
                                            <em class="fa fa-edit"></em>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $grades->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>

    <script>
        const app = new Vue({
            el: "#gradeForm",
            data: {
                form: {
                    name: "",
                    group_id: "",
                    basic_salary: "",
                    home_rent: "",
                    medical_fee: "",
                    transport_fee: "",
                    food_fee: "",
                    total_salary: ""
                },
                groups: [],
            },
            methods: {
                async fetchGroups() {
                    const {data} = await axios.get(`/hr/api/v1/groups`);
                    this.groups = data;
                },
                async getGroupDetails() {
                    const {data} = await axios.get(`/hr/api/v1/group-details/${this.form.group_id}`);
                    let {medical_fee, transport_fee, food_fee} = data;
                    this.form = {...this.form, ...{medical_fee, transport_fee, food_fee}};
                },
                submitGradeForm() {
                    let request = "";
                    if (this.form.id) {
                        request = axios.put(`/hr/api/v1/grade/${this.form.id}`, this.form);
                    } else {
                        request = axios.post(`/hr/api/v1/grade`, this.form);
                    }

                    request.then(() => {
                        location.reload();
                    })
                },
                getGradeEditData(id) {
                    axios.get(`/hr/api/v1/grade/edit/${id}`)
                        .then(({data}) => {
                            this.form = data;
                            this.form.medical_fee = data.group.medical_fee
                            this.form.transport_fee = data.group.transport_fee
                            this.form.food_fee = data.group.food_fee
                        });
                }
            },
            computed: {
                basicSalary() {
                    return parseInt(!this.form.basic_salary || isNaN(this.form.basic_salary) ? 0 : this.form.basic_salary);
                },
                homeRent() {
                    this.form.home_rent = this.basicSalary * (50 / 100);
                    return this.form.home_rent;
                },
                medicalFee() {
                    return parseInt(!this.form.medical_fee || isNaN(this.form.medical_fee) ? 0 : this.form.medical_fee);
                },
                transportFee() {
                    return parseInt(!this.form.transport_fee || isNaN(this.form.transport_fee) ? 0 : this.form.transport_fee);
                },
                foodFee() {
                    return parseInt(!this.form.food_fee || isNaN(this.form.food_fee) ? 0 : this.form.food_fee);
                },
                totalSalary() {
                    this.form.total_salary = this.foodFee + this.transportFee + this.medicalFee + this.homeRent + this.basicSalary;
                    return this.form.total_salary;
                }
            },
            watch: {
                'form.group_id': function () {
                    this.getGroupDetails();
                },
            },
            async mounted() {
                await this.fetchGroups();
            }
        });

        function editGrade(id) {
            app.getGradeEditData(id);
        }

    </script>
@endpush

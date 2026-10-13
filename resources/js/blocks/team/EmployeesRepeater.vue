<script setup lang="ts">
   
interface Props {
    block: {
        type: string;
        data: Department[];
    }
}
    
interface Department {
    department_name: string;
    employees: Employee[];
}

interface Employee {
    employee_image: string;
    employee_name: string;
    position: string;
    phone_number_1: string;
    phone_number_2: string;
    email: string;
}

const props = defineProps<Props>();

</script>

<template>
    <section class="section-employee-repeater py-24">
        <div class="container">
            <div v-if="props.block.data && props.block.data.length > 0" class="department-repeater flex flex-col gap-24">
                <div v-for="(department, deptIndex) in props.block.data" :key="deptIndex" class="department">
                    <h6 class="uppercase department-name">{{ department.department_name }}</h6>
                    <div class="employee-repeater grid grid-cols-1 ,d:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
                        <div v-for="(employee, empIndex) in department.employees" :key="empIndex" class="employee">
                            <img v-if="employee.employee_image" :src="employee.employee_image"
                                :alt="employee.employee_name" class="mb-5 w-full h-auto" />
                            <h6 class="">{{ employee.employee_name }}</h6>
                            <p v-if="employee.position" class="employee-position">{{ employee.position }}</p>
                            <p v-if="employee.phone_number_1" class="">{{ employee.phone_number_1 }}</p>
                            <p v-if="employee.phone_number_2" class="">{{ employee.phone_number_2 }}</p>
                            <p v-if="employee.email" class="">{{ employee.email }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <h3 class="text-center">No employees to display.</h3>
            </div>
        </div>
    </section>
</template>
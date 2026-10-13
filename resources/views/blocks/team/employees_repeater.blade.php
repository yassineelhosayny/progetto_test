<section class="section-employee-repeater py-24">
    <div class="container">
        @if(isset($block['data']['departments']) && is_array($block['data']['departments']) && count($block['data']['departments']) > 0)
            <div class="department-repeater flex flex-col gap-24">
                @foreach($block['data']['departments'] as $department)
                    <div class="department">
                        <h6 class="uppercase department-name">{{ $department['department_name'] }}</h6>
                        <div class="employee-repeater grid grid-cols-1 ,d:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
                            @foreach ($department['employees'] as $employee)
                            <div class="employee">
                                    @if(isset($employee['employee_image']))<img class="mb-5" src="{{ Storage::url($employee['employee_image']) }}" alt="{{ $employee['employee_name'] }}" class="w-full h-auto" />@endif
                                    <h6 class="">{{ $employee['employee_name'] }}</h6>
                                    @if(isset($employee['position']))<p class="employee-position">{{ $employee['position'] }}</p>@endif
                                    @if(isset($employee['phone_number_1']))<p class="">{{ $employee['phone_number_1'] }}</p>@endif
                                    @if(isset($employee['phone_number_2']))<p class="">{{ $employee['phone_number_2'] }}</p>@endif
                                    @if(isset($employee['email']))<p class="">{{ $employee['email'] }}</p>@endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <h3 class="text-center">No employees to display.</h3>
        @endif
        <!-- <pre>
            @php
            var_dump($block)
        @endphp
        </pre> -->
    </div>
</section>
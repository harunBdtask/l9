<?php

return [
    [
        'title' => 'HR KIT',
        'priority' => 7500,
        'url' => '',
        'icon' => '',
        'class' => 'nav-header hidden-folded',
        'items' => []
    ],
    [
        'title' => 'Employee',
        'priority' => 7501,
        'icon' => '',
        'url' => '',
        'items' => [
            [
                'title' => 'Employee Entry',
                'icon' => '',
                'url' => url('/hr/employee/create'),
            ],
            [
                'title' => 'Worker List',
                'icon' => '',
                'url' => url('/hr/employee-list'),
            ],
            [
                'title' => 'Staff List',
                'icon' => '',
                'url' => url('/hr/employee-staff-list'),
            ],
            [
                'title' => 'Management List',
                'icon' => '',
                'url' => url('/hr/employee-management-list'),
            ],
            [
                'title' => 'Check List',
                'icon' => '',
                'url' => url('/hr/employee/checklist'),
            ],
            [
                'title' => 'Salary History',
                'icon' => '',
                'url' => url('/hr/employee/salary-history'),
            ],
            [
                'title' => 'Identity Card',
                'icon' => '',
                'url' => url('/hr/employee/identity-card'),
            ],
            [
                'title' => 'Appointment Letter',
                'icon' => '',
                'url' => url('/hr/employee/appointment-letter'),
            ],
            [
                'title' => 'Disciplinary Information',
                'icon' => '',
                'url' => url('/hr/employee/disciplinary-information'),
            ],
        ]
    ],

    [
        'title' => 'Attendance',
        'priority' => 7502,
        'icon' => '',
        'url' => '',
        'items' => [
            [
                'title' => 'Attendance Dashboard',
                'icon' => '',
                'url' => url('/hr/attendance/dashboard'),
            ],
            [
                'title' => 'Attendance List',
                'icon' => '',
                'url' => url('/hr/attendance'),
            ],
            [
                'title' => 'Manual Attendance List',
                'icon' => '',
                'url' => url('/hr/attendance/manual-entry'),
            ],
            [
                'title' => 'Manual Attendance Create',
                'icon' => '',
                'url' => url('/hr/attendance/manual-entry/create'),
            ],
            [
                'title' => 'Attendance Checklist',
                'icon' => '',
                'url' => url('/hr/attendance/check-list'),
            ],
            [
                'title' => 'Absent Employee List',
                'icon' => '',
                'url' => url('/hr/attendance/absent-list'),
            ],
            [
                'title' => 'OT Approval List',
                'icon' => '',
                'url' => url('/hr/attendance/ot-approval-list'),
            ],
            [
                'title' => 'Night OT List',
                'icon' => '',
                'url' => url('/hr/attendance/night-ot-list'),
            ],
            [
                'title' => 'Attendance Profile',
                'icon' => '',
                'url' => url('/hr/attendance/profile'),
            ],
            [
                'title' => 'Continuous Absent Report',
                'icon' => '',
                'url' => url('/hr/attendance/continuous-absent/report'),
            ],
            [
                'title' => 'Employee Job Card',
                'icon' => '',
                'url' => url('/hr/attendance/employee-job-card/regular'),
            ],
            [
                'title' => 'Employee Job Card (FD)',
                'icon' => '',
                'url' => url('/hr/attendance/employee-job-card'),
            ],
            [
                'title' => 'Daily Roasting',
                'icon' => '',
                'url' => url('/hr/attendance/daily-roasting'),
            ],
            [
                'title' => 'Reports',
                'icon' => '',
                'url' => '',
                'items' => [
                    [
                        'title' => 'Monthly Attendance',
                        'icon' => '',
                        'url' => url('/hr/attendance/report/monthly-attendance'),
                    ],
                    [
                        'title' => 'Monthly Attendance & OT',
                        'icon' => '',
                        'url' => url('/hr/attendance/report/monthly-attendance-v2'),
                    ],
                    [
                        'title' => 'Daily Attendance',
                        'icon' => '',
                        'url' => url('/hr/attendance/report/daily-attendance'),
                    ],
                    [
                        'title' => 'Daily Attendance Report',
                        'icon' => '',
                        'url' => url('/hr/attendance/report/daily-attendence-report'),
                    ],
                    [
                        'title' => 'Daily Workers Absent',
                        'icon' => '',
                        'url' => url('/hr/attendance/report/daily-workers-absent-report'),
                    ],
                ]
            ]
        ]
    ],

    [
        'title' => 'Leave',
        'priority' => 7503,
        'icon' => '',
        'url' => '',
        'items' => [
            [
                'title' => 'Application',
                'icon' => '',
                'url' => url('/hr/leave/application'),
            ],
            [
                'title' => 'Application List',
                'icon' => '',
                'url' => url('/hr/leave/application-list'),
            ],
            [
                'title' => 'Reports',
                'icon' => '',
                'url' => '',
                'items' => [
                    [
                        'title' => 'Individual Leaves',
                        'icon' => '',
                        'url' => url('/hr/leave/reports/individual-leave-report'),
                    ],
                    [
                        'title' => 'Yearly Leaves',
                        'icon' => '',
                        'url' => url('/'),
                    ],
                    [
                        'title' => 'Monthly Leaves',
                        'icon' => '',
                        'url' => url('/'),
                    ],
                ]
            ]
        ]
    ],

    [
        'title' => 'Payroll',
        'priority' => 7504,
        'icon' => '',
        'url' => '',
        'items' => [
            [
                'title' => 'Process Payment',
                'icon' => '',
                'url' => url('/hr/payroll/process-payment'),
            ],
            [
                'title' => 'Print PaySlip',
                'icon' => '',
                'url' => url('/hr/payroll/print-payslip'),
            ],
            [
                'title' => 'Reports',
                'icon' => '',
                'url' => '',
                'items' => [
                    [
                        'title' => 'Monthly Pay Sheet',
                        'icon' => '',
                        'url' => url('/'),
                    ],
                    [
                        'title' => 'Monthly Pay Summary',
                        'icon' => '',
                        'url' => url('/'),
                    ],
                    [
                        'title' => 'Holiday Pay Sheet',
                        'icon' => '',
                        'url' => url('/'),
                    ],
                    [
                        'title' => 'Extra OT Sheet',
                        'icon' => '',
                        'url' => url('/'),
                    ],
                    [
                        'title' => 'Bank Salary Sheet',
                        'icon' => '',
                        'url' => url('/'),
                    ],
                ]
            ]
        ]
    ],

    [
        'title' => 'Settings',
        'priority' => 7505,
        'icon' => '&#xe1bd;',
        'url' => '',
        'items' => [
            [
                'title' => 'Department',
                'icon' => '',
                'url' => url('/hr/departments'),
            ],
            [
                'title' => 'Sections',
                'icon' => '',
                'url' => url('/hr/sections'),
            ],
            [
                'title' => 'Designations',
                'icon' => '',
                'url' => url('/hr/designations'),
            ],

            [
                'title' => 'Groups',
                'icon' => '',
                'url' => url('/hr/groups'),
            ],

            [
                'title' => 'Grades',
                'icon' => '',
                'url' => url('/hr/grades'),
            ],

            [
                'title' => 'Leave Settings',
                'icon' => '',
                'url' => url('/hr/leave-settings'),
            ],

            [
                'title' => 'Holiday',
                'icon' => '',
                'url' => url('/hr/holidays'),
            ],

            [
                'title' => 'Banks',
                'icon' => '',
                'url' => url('/hr/banks'),
            ],

            [
                'title' => 'Shifts',
                'icon' => '',
                'url' => url('/hr/shifts'),
            ],

//            [
//                'title' => 'Festival Leaves',
//                'icon' => '',
//                'url' => url('/hr/festival-leaves'),
//            ],
            [
                'title' => 'Office Time Settings',
                'icon' => '',
                'url' => url('/hr/office-time-settings'),
            ],

        ]
    ],


];

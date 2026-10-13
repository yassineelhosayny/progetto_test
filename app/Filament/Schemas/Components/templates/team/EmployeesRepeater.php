<?php

namespace App\Filament\Schemas\Components\templates\team;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;


class EmployeesRepeater
{
    public static function make(): Block
    {
        return Block::make('employees_repeater')
            ->label('Employees Section')
            ->icon('heroicon-o-star')
            ->schema([
                Repeater::make('departments')
                    ->label('Departments')
                    ->schema([
                        TextInput::make('department_name')
                            ->label('Name of the Department')
                            ->required()
                            ->maxLength(100),
                        Repeater::make('employees')
                            ->label('Employees')
                            ->schema([
                                FileUpload::make('employee_image')
                                    ->label('Employee Picture')
                                    ->image()
                                    ->maxSize(2048) // 2MB
                                    ->directory('pages/team')
                                    ->disk('public')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
                                TextInput::make('employee_name')
                                    ->label('Employee Name')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('position')
                                    ->label('Position')
                                    ->maxLength(100),
                                TextInput::make('phone_number_1')
                                    ->label('Phone Number 1')
                                    ->maxLength(100),
                                TextInput::make('phone_number_2')
                                    ->label('Phone Number 2')
                                    ->maxLength(100),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->maxLength(100),
                            ])
                            ->minItems(1)
                            ->maxItems(20)
                            ->columns(2)
                            ->collapsible()
                            ->collapsed(true)
                            ->createItemButtonLabel('Add Employee')
                    ])
                    ->collapsible()
                    ->collapsed(true)
                    ->createItemButtonLabel('Add Department')
            ]);
    }
}
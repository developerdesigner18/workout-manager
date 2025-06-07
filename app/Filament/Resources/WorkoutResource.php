<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkoutResource\Pages;
use App\Filament\Resources\WorkoutResource\RelationManagers;
use App\Models\Workout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkoutResource extends Resource
{
    protected static ?string $model = Workout::class;
    protected static ?string $navigationIcon = 'heroicon-o-fire';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->rows(3),
                Forms\Components\TextInput::make('trainer')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('date')
                    ->required()
                    ->after('now'),
                Forms\Components\TextInput::make('slots')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('trainer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slots')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created by')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name'),
                Tables\Filters\Filter::make('is_active')
                    ->query(fn ($query) => $query->where('is_active', true)),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkouts::route('/'),
            'create' => Pages\CreateWorkout::route('/create'),
            'edit' => Pages\EditWorkout::route('/{record}/edit'),
        ];
    }
}

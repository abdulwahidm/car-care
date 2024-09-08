<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarStoreResource\Pages;
use App\Filament\Resources\CarStoreResource\RelationManagers;
use App\Filament\Resources\CarStoreResource\RelationManagers\PhotosRelationManager;
use App\Models\CarStore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarStoreResource extends Resource
{
    protected static ?string $model = CarStore::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('phone_number')
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('cs_name')
                ->required()
                ->maxLength(255),

                Forms\Components\Select::make('is_open')
                ->options([
                    true => 'Open',
                    false => 'Not Open',
                ])
                ->required(),

                Forms\Components\Select::make('is_full')
                ->options([
                    true => 'Full Booked',
                    false => 'Available',
                ])
                ->required(),

                Forms\Components\Select::make('city_id')
                ->relationship('city', 'name')
                ->searchable()
                ->preload()
                ->required(),

                Forms\Components\Repeater::make('storeServices')
                ->relationship()
                ->schema([
                    Forms\Components\Select::make('car_service_id')
                    ->relationship('service', 'name')
                    ->required()
                ]),
                
                Forms\Components\FileUpload::make('thumbnail')
                ->image()
                ->required(),

                Forms\Components\Textarea::make('address')
                ->required()
                ->rows(10)
                ->cols(20),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Toko')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_open')
                    ->label('Buka ?')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_full')
                    ->label('Tersedia ?')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->size(50),

            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('city_id')
                    ->label('Filter by City')
                    ->relationship('city', 'name')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('car_service_id')
                    ->label('Filter by Service')
                    ->relationship('storeServices.service', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            PhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarStores::route('/'),
            'create' => Pages\CreateCarStore::route('/create'),
            'edit' => Pages\EditCarStore::route('/{record}/edit'),
        ];
    }
}

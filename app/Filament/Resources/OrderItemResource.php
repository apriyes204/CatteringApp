<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderItemResource\Pages;
use App\Filament\Resources\OrderItemResource\RelationManagers;
use App\Models\Menu;
use App\Models\OrderItem;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class OrderItemResource extends Resource
{
    protected static ?string $model = OrderItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([
                    'sm'=>1,
                    'md'=>1
                ])
                ->schema([
                    Wizard::make([
                        Step::make('Menu Makanan')
                        ->schema([
                            Grid::make([
                                'sm'=>1,
                                'md'=>1
                            ])
                            ->schema([
                                Repeater::make('Pilih Menu')
                                ->schema([
                                    Grid::make([
                                        'sm'=>1,
                                        'md'=>4
                                    ])
                                    ->schema([
                                        Select::make('menus_id')
                                            ->label('Nama Menu')
                                            ->options(Menu::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->reactive()
                                            ->required()->afterStateUpdated(function ($state, callable $set, callable $get ){
                                                $menu = Menu::find($state);
                                                if($menu ) {
                                                    $price = (number_format($menu->price));
                                                    $set('price', $price);
                                                }})
                                            ,
                                        TextInput::make('price')
                                            ->label('Harga')
                                            ->prefix('Rp')
                                            ->default('0')
                                            ->reactive()
                                            ->disabled(),
                                        TextInput::make('amount')
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->reactive()
                                            ->inputMode('decimal')
                                            ->default(0)
                                            ->required()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get ){
                                                $amount=$state;
                                                $price = floatval($get('price'));
                                                $total = $amount * $price;
                                                $set('total', number_format($total*1000));
                                            })
                                            ,
                                        TextInput::make('total')
                                            ->reactive()
                                            ->disabled()
                                            ->default(0),
                                        ])

                                    ])
                                ])
                                ,
                        ]),
                        Step::make('Data Pembeli')
                        ->schema([
                            Select::make('users_id')
                                ->label('Nama Pembeli')
                                ->options(User::all()->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('address')
                                ->label('Alamat')
                                ->required(),
                            TextInput::make('shipping_price')
                                ->label('Biaya Admin')
                                ->disabled()
                                ->default(number_format(10000/1))

                        ]),
                        Step::make('Pembayaran')
                        ->schema([
                            TextInput::make('grand_price')
                            ->label('Total Harga')
                            ->disabled()
                            ->default(0)
                            ->reactive(),
                        ])
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_details_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grand_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderItems::route('/'),
            'create' => Pages\CreateOrderItem::route('/create'),
            'edit' => Pages\EditOrderItem::route('/{record}/edit'),
        ];
    }
}

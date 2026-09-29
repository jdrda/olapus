<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_table_contains_new_profile_fields_and_not_legacy_profile_fields(): void
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'company_name',
            'contact_name',
            'contact_email',
            'contact_phone',
            'company_id',
            'vat_id',
            'billing_country',
            'billing_phone',
            'shipping_country',
            'shipping_phone',
            'customer_number',
            'external_id',
            'locale',
            'timezone',
            'notice',
        ]));

        $this->assertFalse(Schema::hasColumn('users', 'fname'));
        $this->assertFalse(Schema::hasColumn('users', 'fax'));
        $this->assertFalse(Schema::hasColumn('users', 'da_street'));
    }

    public function test_user_can_update_own_optional_profile_fields(): void
    {
        $user = User::factory()->create([
            'active' => true,
        ]);
        $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $user->assignRole($role);

        $this->actingAs($user)->put(route('admin.profile.update'), [
            'name' => 'Jan Novak',
            'email' => 'jan.novak@example.test',
            'company_name' => 'Example s.r.o.',
            'contact_name' => 'Jan Novak',
            'contact_email' => 'kontakt@example.test',
            'contact_phone' => '+420777123456',
            'company_id' => '12345678',
            'vat_id' => 'CZ12345678',
            'website' => 'https://example.test',
            'customer_number' => 'C-10025',
            'external_id' => 'crm-8842',
            'locale' => 'cs-CZ',
            'timezone' => 'Europe/Prague',
            'billing_name' => 'Example s.r.o.',
            'billing_street' => 'Hlavni',
            'billing_house_number' => '10',
            'billing_city' => 'Praha',
            'billing_zip' => '110 00',
            'billing_country' => 'cz',
            'billing_email' => 'fakturace@example.test',
            'billing_phone' => '+420222123456',
            'shipping_name' => 'Sklad Example',
            'shipping_city' => 'Brno',
            'shipping_country' => 'sk',
            'shipping_email' => 'sklad@example.test',
            'shipping_phone' => '+421901123456',
            'notice' => 'Interni poznamka uzivatele.',
            // Self-edit must not change administration-only attributes.
            'active' => 0,
            'roles' => [],
        ])->assertRedirect(route('admin.profile.edit'));

        $user->refresh();

        $this->assertSame('Jan Novak', $user->name);
        $this->assertSame('Example s.r.o.', $user->company_name);
        $this->assertSame('+420777123456', $user->contact_phone);
        $this->assertSame('CZ', $user->billing_country);
        $this->assertSame('SK', $user->shipping_country);
        $this->assertSame('Europe/Prague', $user->timezone);
        $this->assertTrue($user->active);
        $this->assertTrue($user->hasRole('editor'));
    }

    public function test_optional_profile_values_are_validated_when_filled(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('admin.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'contact_email' => 'not-an-email',
            'contact_phone' => '777123456',
            'website' => 'not-a-url',
            'billing_country' => 'CZE',
            'billing_email' => 'bad-email',
            'shipping_phone' => '+000',
            'locale' => 'not a locale',
            'timezone' => 'Mars/Olympus',
        ])->assertSessionHasErrors([
            'contact_email',
            'contact_phone',
            'website',
            'billing_country',
            'billing_email',
            'shipping_phone',
            'locale',
            'timezone',
        ]);
    }
}

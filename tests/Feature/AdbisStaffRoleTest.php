<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AdbisStaffRoleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.env' => 'testing',
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'view.paths' => [resource_path('views')],
            'view.compiled' => sys_get_temp_dir(),
            'cache.default' => 'array',
            'session.driver' => 'array',
        ]);

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        Artisan::call('migrate:fresh', ['--force' => true]);
    }

    private function createUser(string $role): User
    {
        return User::create([
            'name' => ucwords($role) . ' User',
            'email' => str_replace(' ', '.', $role) . '@lpkia.ac.id',
            'password' => 'password123',
            'role' => $role,
        ]);
    }

    public function test_adbis_staff_can_view_and_update_posts_pages_and_events()
    {
        $staff = $this->createUser('adbis staff');
        $category = Category::create(['name' => 'Akademik', 'slug' => 'akademik']);

        $post = Post::create([
            'category_id' => $category->id,
            'user_id' => $staff->id,
            'title' => 'Judul Post Awal',
            'slug' => 'judul-post-awal',
            'content' => '<p>Konten awal</p>',
            'status' => 'published',
        ]);

        $page = Page::create([
            'title' => 'Halaman Awal',
            'slug' => 'halaman-awal',
            'content' => '<p>Isi halaman awal</p>',
            'status' => 'published',
        ]);

        // Dashboard
        $this->actingAs($staff)->get(route('admin.dashboard'))->assertStatus(200);

        // Posts index, create, store, edit & update
        $this->actingAs($staff)
            ->get(route('admin.posts.index'))
            ->assertStatus(200)
            ->assertSee('Buat Postingan Baru');

        $this->actingAs($staff)->get(route('admin.posts.create'))->assertStatus(200);

        $this->actingAs($staff)
            ->post(route('admin.posts.store'), [
                'title' => 'Postingan Baru Staff',
                'category_id' => $category->id,
                'content' => '<p>Konten baru staff</p>',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Postingan Baru Staff',
        ]);

        $this->actingAs($staff)->get(route('admin.posts.edit', $post->id))->assertStatus(200);

        $this->actingAs($staff)
            ->put(route('admin.posts.update', $post->id), [
                'title' => 'Judul Post Diperbarui',
                'category_id' => $category->id,
                'content' => '<p>Konten baru</p>',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Judul Post Diperbarui',
        ]);

        // Pages index, create, store, edit & update
        $this->actingAs($staff)
            ->get(route('admin.pages.index'))
            ->assertStatus(200)
            ->assertSee('Buat Halaman Baru');

        $this->actingAs($staff)->get(route('admin.pages.create'))->assertStatus(200);

        $this->actingAs($staff)
            ->post(route('admin.pages.store'), [
                'title' => 'Halaman Baru Staff',
                'content' => '<p>Isi halaman baru staff</p>',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $this->assertDatabaseHas('pages', [
            'title' => 'Halaman Baru Staff',
        ]);

        $this->actingAs($staff)->get(route('admin.pages.edit', $page->id))->assertStatus(200);

        $this->actingAs($staff)
            ->put(route('admin.pages.update', $page->id), [
                'title' => 'Halaman Diperbarui',
                'content' => '<p>Isi baru</p>',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'Halaman Diperbarui',
        ]);

        // Events & Kegiatan index & update (preserves other tri-column fields)
        Setting::set('homepage_tricolumn_content', json_encode([
            'col1_title' => 'Berita Terbaru',
            'col1_show_news' => true,
            'col2_title' => 'Agenda & Event',
            'col2_events' => [],
            'col3_title' => 'Tentang Program Studi',
        ]));

        $this->actingAs($staff)->get(route('admin.events.index'))->assertStatus(200);

        $this->actingAs($staff)
            ->post(route('admin.events.update'), [
                'col2_title' => 'Agenda & Kegiatan Terbaru',
                'events' => [
                    [
                        'day' => '20',
                        'month' => 'Nov',
                        'title' => 'Seminar Administrasi Digital',
                        'meta' => '09:00 - selesai | Aula LPKIA',
                    ],
                ],
            ])
            ->assertRedirect(route('admin.events.index'));

        $updatedTriColumn = json_decode(Setting::get('homepage_tricolumn_content'), true);
        $this->assertSame('Agenda & Kegiatan Terbaru', $updatedTriColumn['col2_title']);
        $this->assertSame('Berita Terbaru', $updatedTriColumn['col1_title']);
        $this->assertCount(1, $updatedTriColumn['col2_events']);
        $this->assertSame('Seminar Administrasi Digital', $updatedTriColumn['col2_events'][0]['title']);
    }

    public function test_adbis_staff_cannot_create_delete_or_access_admin_only_menus()
    {
        $staff = $this->createUser('adbis staff');
        $category = Category::create(['name' => 'Umum', 'slug' => 'umum']);

        $post = Post::create([
            'category_id' => $category->id,
            'user_id' => $staff->id,
            'title' => 'Post Test',
            'slug' => 'post-test',
            'content' => 'Test',
            'status' => 'published',
        ]);

        $page = Page::create([
            'title' => 'Page Test',
            'slug' => 'page-test',
            'content' => 'Test',
            'status' => 'published',
        ]);

        // Blocked Post actions (delete & bulk)
        $this->actingAs($staff)->delete(route('admin.posts.destroy', $post->id))->assertStatus(403);
        $this->actingAs($staff)->post(route('admin.posts.bulk'), [])->assertStatus(403);

        // Blocked Page actions (delete & bulk)
        $this->actingAs($staff)->delete(route('admin.pages.destroy', $page->id))->assertStatus(403);
        $this->actingAs($staff)->post(route('admin.pages.bulk'), [])->assertStatus(403);

        // Blocked Admin-only sections
        $this->actingAs($staff)->get(route('admin.lecturers.index'))->assertStatus(403);
        $this->actingAs($staff)->get(route('admin.org-members.index'))->assertStatus(403);
        $this->actingAs($staff)->get(route('admin.sliders.index'))->assertStatus(403);
        $this->actingAs($staff)->get(route('admin.settings.index'))->assertStatus(403);
        $this->actingAs($staff)->get(route('admin.customizer.index'))->assertStatus(403);
        $this->actingAs($staff)->get(route('admin.menus.index'))->assertStatus(403);
        $this->actingAs($staff)->get(route('admin.schedule.index'))->assertStatus(403);
    }

    public function test_admin_retains_full_access()
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)->get(route('admin.posts.create'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.pages.create'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.events.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.customizer.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.settings.index'))->assertStatus(200);
    }
}

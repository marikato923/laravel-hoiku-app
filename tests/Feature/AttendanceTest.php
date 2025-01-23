<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Child;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 登園時
     * 
     * @return void
     */
    public function testMarkArrival()
    {
        $child = Child::factory()->create();

        $response = $this->postJson('/api/attendance/arrival', [
            'children' => [$child->id],
            'pickup_name' => '保護者A',
            'pickup_time' => '17:00',
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => "{$child->first_name}さんの登園を記録しました。",
        ]);

        $this->assertDatabaseHas('attendances', [
            'child_id' => $child->id,
            'pickup_name' => '保護者A',
            'pickup_time' => '17:00',
        ]);
    }

    /**
     * 降園時
     *
     * @return void
     */
    public function testMarkDeparture()
    {

        $child = Child::factory()->create();

        Attendance::create([
            'child_id' => $child->id,
            'departure_time' => now(),
        ]);

        $response = $this->postJson('/api/attendance/departure', [
            'children' => [$child->id],
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'messages' => ["{$child->first_name}さんの降園を記録しました。"],
        ]);

        $this->assertDatabaseHas('attendances', [
            'child_id' => $child->id,
            'departure_time' => now()->subSeconds(36)->format('Y-m-d H:i:00'),
        ]);
    }

    /**
     * ユーザーと紐づかない子供の打刻を試みた場合
     *
     * @return void
     */
    public function testMarkDepartureWithInvalidChild()
    {
        $response = $this->postJson('/api/attendance/departure', [
            'children' => [999], 
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'message' => '選択されたchildren.0は正しくありません。',
        ]);
    }

    /**
     * バリデーションエラー
     *
     * @return void
     */
    public function testMarkArrivalValidationError()
    {
        $response = $this->postJson('/api/attendance/arrival', [
            'children' => [],
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['children']);
    }

    /**
     * ボタンの非活性/活性
     *
     * @return void
     */
    public function testArrivalButtonDisabledAfterArrival()
    {
        $child = Child::factory()->create();

        $response = $this->postJson('/api/attendance/arrival', [
            'children' => [$child->id],
            'pickup_name' => '保護者A',
            'pickup_time' => '17:00',
        ]);

        $response->assertJson([
            'message' => "{$child->first_name}さんの登園を記録しました。",
        ]);
    }
}

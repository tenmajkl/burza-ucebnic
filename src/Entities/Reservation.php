<?php

declare(strict_types=1);

namespace App\Entities;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Traits\DateTimes;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\ORM\Select\QueryBuilder;
use Cycle\ORM\Entity\Behavior;
use Lemon\Contracts\Kernel\Injectable;
use Lemon\Kernel\Container;

#[Entity()]
#[Behavior\CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
#[Behavior\UpdatedAt(
    field: 'updatedAt',
    column: 'updated_at'
)]
class Reservation implements \JsonSerializable, Injectable
{
    use DateTimes;

    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'string')]
    public string $hash;

    #[HasMany(target: Message::class)]
    public array $messages = [];

    public function __construct(
        #[BelongsTo(target: Offer::class)]
        public Offer $offer,
        #[BelongsTo(target: User::class)]
        public User $user,
        #[Column(type: 'int', typecast: ReservationStatus::class)]
        public ReservationStatus $status,
    ) {
        // Profi token generation coolfido aproves
        $this->hash = sha1(str_shuffle(base64_encode(str_shuffle($this->offer->id.rand().time()).sha1(rand().time().$user->id))));
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'active' => $this->active,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'author' => $this->user->email,
            'offer' => $this->offer->jsonSerialize()
        ];
    }

    public static function fromInjection(Container $container, mixed $value): ?self
    {
        $user_id = $container->get(Auth::class)->user()->id;
        return $container->get(ORM::class)->getORM()
                                          ->getRepository(self::class)
                                          ->select()
                                          ->where(['id' => (int) $value])
                                          ->where(
                                              static function(QueryBuilder $select) use ($user_id) {
                                                    $select
                                                        ->where(['user.id' => $user_id])
                                                        ->orWhere(['offer.user.id' => $user_id])
                                                    ;
                                              }

                                          )
                                          ->fetchOne();
    }
}

<?php

declare(strict_types=1);

enum Permission
{
    case ViewCards;
    case ViewTransactions;
    case BlockCard;
    case UnblockCard;
}
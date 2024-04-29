<?php
namespace backend\bus;

use backend\interfaces\BUSInterface;
use InvalidArgumentException;
use backend\dao\CouponsDAO;

class CouponsBUS implements BUSInterface
{
    private $couponsList = array();
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CouponsBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->refreshData();
    }

    public function getAllModels(): array
    {
        return $this->couponsList;
    }

    public function refreshData(): void
    {
        $this->couponsList = CouponsDAO::getInstance()->getAll();
    }

    public function getModelById(int $id)
    {
        return CouponsDAO::getInstance()->getById($id);
    }

    public function getModelByCode(string $code)
    {
        for ($i = 0; $i < count($this->couponsList); $i++) {
            if ($this->couponsList[$i]->getCode() == $code) {
                return $this->couponsList[$i];
            }
        }
        return null;
    }

    public function addModel($couponsModel)
    {
        $this->validateModel($couponsModel);
        $result = CouponsDAO::getInstance()->insert($couponsModel);
        if ($result) {
            $this->couponsList[] = $couponsModel;
            $this->refreshData();
        }
        return $result;
    }

    public function updateModel($couponsModel): int
    {
        $this->validateModel($couponsModel);
        $result = CouponsDAO::getInstance()->update($couponsModel);
        if ($result) {
            $index = array_search($couponsModel, $this->couponsList);
            $this->couponsList[$index] = $couponsModel;
            $this->refreshData();
        }
        return $result;
    }

    public function checkForDuplicateCode($code)
    {
        for ($i = 0; $i < count($this->couponsList); $i++) {
            if ($this->couponsList[$i]->getCode() == $code) {
                return true;
            }
        }
        return false;
    }

    public function deleteModel($id)
    {
        $result = CouponsDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->couponsList, 'id'));
            unset($this->couponsList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function validateModel($couponsModel)
    {
        if (
            empty($couponsModel->getCode()) ||
            empty($couponsModel->getQuantity()) ||
            empty($couponsModel->getPercent()) ||
            empty($couponsModel->getExpired()) ||
            empty($couponsModel->getDescription()) ||
            $couponsModel->getCode() == null ||
            $couponsModel->getQuantity() == null ||
            $couponsModel->getPercent() == null ||
            $couponsModel->getExpired() == null ||
            $couponsModel->getDescription() == null ||
            $couponsModel->getQuantity() < 0 ||
            $couponsModel->getPercent() < 0 ||
            $couponsModel->getPercent() > 100
        ) {
            throw new InvalidArgumentException("Invalid coupons model");
        }
    }

    public function searchModel(string $value, array $columns)
    {
        return CouponsDAO::getInstance()->search($value, $columns);
    }

    public function searchBetweenDate($before, $after)
    {
        return array_filter($this->couponsList, function ($coupon) use ($before, $after) {
            return $coupon->getExpired() >= $before && $coupon->getExpired() <= $after;
        });
    }

    public function searchBeforeDate($before)
    {
        return array_filter($this->couponsList, function ($coupon) use ($before) {
            return $coupon->getExpired() <= $before;
        });
    }

    public function searchAfterDate($after)
    {
        return array_filter($this->couponsList, function ($coupon) use ($after) {
            return $coupon->getExpired() >= $after;
        });
    }

    public function applyDiscount($couponsModel, $orderTotal): float
    {
        $discount = $couponsModel->getPercent();
        return $orderTotal - ($orderTotal * $discount / 100);
    }
}

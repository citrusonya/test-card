<?php

namespace frontend\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;

class CardForm extends Model
{
    public $pan;
    public $cvc;
    public $cardholder;
    public $expire;
    public const EXPIRE_TIME = 3600;

    public function rules()
    {
        return [
            [['pan', 'cvc', 'cardholder', 'expire'], 'required', 'message' => 'Пожалуйста, заполните поле'],
            [['pan', 'cvc', 'expire'], 'string'],
            [
                ['pan'],
                'string',
                'min'      => 16,
                'max'      => 16,
                'tooShort' => 'Номер карты должен содержать не меннее 16 цифр',
                'tooLong'  => 'Номер карты должен содержать не более 16 цифр'
            ],
            [
                ['cvc'],
                'string',
                'min' => 3,
                'max' => 3,
                'tooShort' => 'Cvc должно содержать не менее 3 цифр',
                'tooLong' => 'Cvc должно содержать не более 3 цифр'
            ],
            [
                ['expire'],
                'string',
                'min' => 4,
                'max' => 4,
                'tooShort' => 'Срок действия карты должен содержать месяц (2 цифры) и год (последние 2 цифры)',
                'tooLong' => 'Срок действия карты должен содержать месяц (2 цифры) и год (последние 2 цифры)'
            ],
            [['pan', 'cvc', 'expire'], 'match', 'pattern' => '/^([+]?[0-9]+)$/'],
            [['created_at', 'updated_at'], 'safe'],
            [
                ['cardholder'],
                'string',
                'max' => 255,
                'min' => 3,
                'tooShort' => 'Пожалуйста, введите инициалы держателя карты'
            ],
            ['cardholder', 'match', 'pattern' => '/^[a-zA-Z \s]{4,}$/i', 'message' => 'Допустимы только латинские буквы'],
            ['pan', 'validateCardNumber'],
        ];
    }

    /**
     * Валидация номера карты
     *
     * @param $number
     * @return bool
     */
    private function validateCardNumber($number): bool
    {
        $sum = '';

        for ($i = strlen($number) - 1; $i >= 0; -- $i) {
            $sum .= $i & 1 ? $number[$i] : $number[$i] * 2;
        }

        return array_sum(str_split($sum)) % 10 === 0;
    }

    /**
     *  Создание криптограммы
     *
     * @throws \Exception
     */
    public function token($token)
    {
        $errorText = '';
        $emptyParams = [];

        $params = [
            'pan'        => $this->pan,
            'cvc'        => $this->cvc,
            'cardholder' => $this->cardholder,
            'expire'     => $this->expire,
        ];

        foreach ($params as $param) {
            if(empty($param)){
                $emptyParams .= $param;
            }
        }

        if (!empty($emptyParams)) {
            $errorText .= 'The request failed. Fields not filled: ' . implode(', ', $emptyParams);
        }

        if (!$this->validate()) {
            $errorText .= 'Field validation error';
        }

        $tokenTime = $this->isAccessTokenValid($token);

        if(($tokenTime - self::EXPIRE_TIME) <= 0) {
            $errorText .= 'Token is not valid';
        }

        if (!empty($errorText)) {
            $error = json_encode(['error' => $errorText]);

            Yii::error($error, 'error');

            return $error;
        }

        $tokenExpire = ((new \DateTime('now', new \DateTimeZone('Europe/Moscow')))
            + $tokenTime)
            ->format('Y-m-d H:i:s');

        $result = Yii::$app->encrypter->encrypt($params .= $tokenExpire);

        Yii::info(json_encode(['info' => $result]), 'success');

        return json_encode([
            'pan'   => $this->maskPan($params['pan']),
            'token' => $token
        ]);
    }

    /**
     * Получение первых 4 и последних 4 цифр
     *
     * @param $number
     * @return string
     */

    private function maskPan($number): string
    {
        $data = [
            mb_substr($number, 0, 4),
            '**',
            mb_substr($number, 12, 16)
        ];

        return implode('', $data);
    }

    /**
     * @throws Exception
     */
    public static function generateAccessToken()
    {
        return Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function isAccessTokenValid($accessToken)
    {
        if (!empty($accessToken)) {
            return (int)substr($accessToken, strrpos($accessToken, '_') + 1);
        }

        return false;
    }
}

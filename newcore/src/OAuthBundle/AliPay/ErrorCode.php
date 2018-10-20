<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月23日
*/
namespace OAuthBundle\AliPay;

class ErrorCode
{
    public function getText()
    {
        return array(
            #即时到帐服务错误代码
            'SELLER_NOT_IN_SPECIFIED_SELLERS' => '抱歉，该收款账户不是指定的收款账户，请确认参数是否正确或咨询您的客户经理',
            'TRADE_SELLER_NOT_MATCH' => '抱歉，该笔交易的卖家已不存在，请联系正确的卖家重新创建交易进行付款',
            'TRADE_BUYER_NOT_MATCH' => '抱歉，您本次支付使用的账户与原先的不一致，请使用原来的账户，或重新创建交易付款',
            'ILLEGAL_FEE_PARAM' => '抱歉，金额传递错误，请确认参数是否正确或咨询您的客户经理',
            'SUBJECT_MUST_NOT_BE_NULL' => '商品名不能为空',
            'TRADE_PRICE_NOT_MATCH' => '抱歉，该商品的交易单价与原先的不一致，请重新创建交易付款',
            'TRADE_QUANTITY_NOT_MATCH' => '抱歉，该商品的购买数量与原先的不一致，请重新创建交易付款',
            'TRADE_TOTALFEE_NOT_MATCH' => '抱歉，该商品的交易金额与原先的不一致，请重新创建交易付款',
            'TRADE_NOT_ALLOWED_PAY' => '抱歉，您不能进行本次支付，请查看该交易是否已超时或已被关闭等',
            'DIRECT_PAY_WITHOUT_CERT_CLOSE' => '未开通非证书余额支付，无法完成支付',
            'FAIL_CREATE_CASHIER_PAY_ORDER' => '抱歉，系统异常，无法创建本次收银台支付订单，请稍后再试',
            'ILLEGAL_EXTRA_COMMON_PARAM' => '抱歉，接口通用回传参数格式不正确，请联系您的商户',
            'ILLEGAL_PAYMENT_TYPE' => '抱歉，接口传递的Payment_type参数错误，请联系您的商户 ',
            'NOT_SUPPORT_GATEWAY' => '抱歉，商户网关配置出错，请联系您的商户',
            'BUYER_SELLER_EQUAL' => '抱歉，买家和卖家不能是同一个账户',
            'SELLER_NOT_EXIST' => '抱歉，卖家账户经验证不存在，请联系您的商户',
            'ILLEGAL_ARGUMENT' => '抱歉，商户传递的接口参数错误，请联系您的商户',
            'TRADE_NOT_FOUND' => '根据交易号无法找到交易',
            'TRADE_GOOD_INFO_NOT_FOUND' => '根据交易号无法找到交易详情',
            'BUYER_EMAIL_ID_MUST_NULL' => '抱歉，该笔交易的买家账户必须为空，请联系您的商户',
            'PRODUCT_NOT_ALLOWED' => '您未开通此产品，暂时无法使用本服务',
            'ROYALTY_RECEIVER_NOT_IN_SPECIFIED_ACCOUNTS' => '抱歉，分润账号不是指定的分润账户，请确保该分润账户已签署分润协议',
            'ROYALTY_LENGTH_ERROR' => '抱歉，分润信息过长，不能超过1000个字符，请检查后重新集成',
            'DEFAULT_BANK_INVALID' => '您传递的默认网银参数不在规定的范围内',
            'DIS_NOT_SIGN_PROTOCOL' => '抱歉，您的分销商没有与支付宝签约，请联系您的商户',
            'SELF_TIMEOUT_NOT_SUPPORT' => '抱歉，商户没有开通自定义超时权限，请联系您的商户',
            'ILLEGAL_OUTTIME_ARGUMENT' => '抱歉，自定义超时时间设置错误，请联系您的商户',
            'EBANK_CERDIT_GW_RULE_NOT_OPEN' => '信用卡未签约（签约到期）或者接口参数未指定开通信用卡支付',
            'DIRECTIONAL_PAY_FORBIDDEN' => '付款受限，请确保收款方有权进行收款',
            'SELLER_ENABLE_STATUS_FORBID' => '卖家状态不正常',
            'ROYALTY_SELLER_ENABLE_STATUS_FORBID' => '抱歉，卖家暂时无法进行收款操作，请联系您的商户',
            'ROYALTY_SELLER_NOT_CERTIFY' => '抱歉，卖家尚未通过认证，不能进行收款，请联系您的商户',
            'ROYALTY_FORAMT_ERROR' => '抱歉，接口传递的分润参数格式错误，请检查后重新集成',
            'ROYALTY_TYPE_ERROR' => '抱歉，接口传递的分润类型错误，请检查后重新集成',
            'ROYALTY_RECEIVE_EMAIL_NOT_EXIST' => '抱歉，分润账户经验证不存在，请联系您的商户',
            'ROYALTY_RECEIVE_EMAIL_NOT_CERTIFY' => '抱歉，分润账户经验证未通过人行验证，请联系您的商户',
            'ROYALTY_PAY_EMAIL_NOT_EXIST' => '抱歉，分润付款账户经验证不存在，请联系您的商户',
            'TAOBAO_ANTI_PHISHING_CHECK_FAIL' => '抱歉，无法付款! 该笔交易可能存在风险，如果您确定本次交易没有问题，请1个小时后再付款',
            'SUBJECT_HAS_FORBIDDENWORD' => '抱歉，无法付款! 请联系商户修改商品名称，再重新购买',
            'BODY_HAS_FORBIDDENWORD' => '抱歉，无法付款! 请联系商户修改商品描述，再重新购买',
            'NEED_CTU_CHECK_PARAMETER_ERROR' => '抱歉，您传递的商户可信任参数权限参数错误',
            'NEED_CTU_CHECK_NOT_ALLOWED' => '抱歉，商户没有可信任参数校验的权限',
            'BUYER_NOT_EXIST' => '抱歉，买家账户经验证不存在',
            'HAS_NO_PRIVILEGE' => '你的当前访问记录丢失，请返回商户网站重新发起付款',

            #即时到帐有密退款服务错误代码
            'ILLEGAL_USER' => '用户ID不正确',
            'BATCH_NUM_EXCEED_LIMIT' => '总比数大于1000',
            'REFUND_DATE_ERROR' => '错误的退款时间',
            'BATCH_NUM_ERROR' => '传入的总笔数格式错误',
            'BATCH_NUM_NOT_EQUAL_TOTAL' => '传入的退款条数不等于数据集解析出的退款条数',
            'SINGLE_DETAIL_DATA_EXCEED_LIMIT' => '单笔退款明细超出限制',
            'NOT_THIS_SELLER_TRADE' => '不是当前卖家的交易',
            'DUBL_TRADE_NO_IN_SAME_BATCH' => '同一批退款中存在两条相同的退款记录',
            'DUPLICATE_BATCH_NO' => '重复的批次号',
            'TRADE_STATUS_ERROR' => '交易状态不允许退款',
            'BATCH_NO_FORMAT_ERROR' => '批次号格式错误',
            'SELLER_INFO_NOT_EXIST' => '卖家信息不存在',
            'PARTNER_NOT_SIGN_PROTOCOL' => '平台商未签署协议',
            'NOT_THIS_PARTNERS_TRADE' => '退款明细非本合作伙伴的交易',
            'DETAIL_DATA_FORMAT_ERROR' => '数据集参数格式错误',
            'PWD_REFUND_NOT_ALLOW_ROYALTY' => '有密接口不允许退分润',
            'NANHANG_REFUND_CHARGE_AMOUNT_ERROR' => '退票面价金额不合法',
            'REFUND_AMOUNT_NOT_VALID' => '退款金额不合法',
            'TRADE_PRODUCT_TYPE_NOT_ALLOW_REFUND' => '交易类型不允许退交易',
            'RESULT_FACE_AMOUNT_NOT_VALID' => '退款票面价不能大于支付票面价',
            'REFUND_CHARGE_FEE_ERROR' => '退收费金额不合法',
            'REASON_REFUND_CHARGE_ERR' => '退收费失败',
            'RESULT_AMOUNT_NOT_VALID' => '退收费金额错误',
            'RESULT_ACCOUNT_NO_NOT_VALID' => '账号无效',
            'REASON_TRADE_REFUND_FEE_ERR' => '退款金额错误',
            'REASON_HAS_REFUND_FEE_NOT_MATCH' => '已退款金额错误',
            'TXN_RESULT_ACCOUNT_STATUS_NOT_VALID' => '账户状态无效',
            'TXN_RESULT_ACCOUNT_BALANCE_NOT_ENOUGH' => '账户余额不足',
            'REASON_REFUND_AMOUNT_LESS_THAN_COUPON_FEE' => '红包无法部分退款',
            'BUYER_ERROR' => '因买家支付宝账户问题不允许退款',

        );
    }
}
<?php

declare(strict_types=1);


namespace App\Tests\Unit\Validator;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Validator\UniqueActiveSku;
use App\Validator\UniqueActiveSkuValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class UniqueActiveSkuValidatorTest extends TestCase
{
    private ExecutionContextInterface $context;
    private UniqueActiveSkuValidator $validator;

    protected function setUp(): void
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator = new UniqueActiveSkuValidator(
            $this->createStub(ProductRepository::class)
        );
    }

    public function testValidateAddsViolationIfActiveSkuExists(): void
    {
        $sku = 'EXISTING-SKU';
        $constraint = new UniqueActiveSku();

        $product = new Product();

        $repository = $this->createMock(ProductRepository::class);
        $repository->expects($this->once())
            ->method('findActiveBySku')
            ->with($sku, null)
            ->willReturn(new Product());

        $validator = new UniqueActiveSkuValidator($repository);
        $validator->initialize($this->context);

        $this->context->method('getObject')->willReturn($product);

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violationBuilder->method('setParameter')->willReturnSelf();
        $violationBuilder->expects($this->once())->method('addViolation');

        $this->context->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($violationBuilder);

        $validator->validate($sku, $constraint);
    }

    public function testValidateDoesNothingIfSkuIsUnique(): void
    {
        $sku = 'UNIQUE-SKU';
        $constraint = new UniqueActiveSku();

        $repository = $this->createStub(ProductRepository::class); // stub, nie mock
        $repository->method('findActiveBySku')->willReturn(null);

        $validator = new UniqueActiveSkuValidator($repository);
        $validator->initialize($this->context);

        $this->context->method('getObject')->willReturn(new Product());
        $this->context->expects($this->never())->method('buildViolation');

        $validator->validate($sku, $constraint);
    }
}

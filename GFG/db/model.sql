CREATE TABLE IF NOT EXISTS    `gfg_catalog_products` (
  `id_product` INT NOT NULL AUTO_INCREMENT,
  `sku` VARCHAR(100) NOT NULL,
  `name` VARCHAR(250) NOT NULL,
  `description` LONGTEXT NOT NULL,
  `price` DOUBLE NOT NULL,
  `brand` VARCHAR(100) NOT NULL,
  `product_image_url` VARCHAR(250) NOT NULL,
  `special_price` DOUBLE NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id_product`))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS    `gfg_categories` (
  `id_categorie` INT NOT NULL,
  `categorie` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_categorie`))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS    `gfg_catalog_product_categories` (
  `id_prod_cat` INT NOT NULL AUTO_INCREMENT,
  `id_categorie` INT NOT NULL,
  `id_product` INT NOT NULL,
  PRIMARY KEY (`id_prod_cat`),
  INDEX `product_idx` (`id_product` ASC),
  INDEX `categorie_idx` (`id_categorie` ASC),
  CONSTRAINT `product`
    FOREIGN KEY (`id_product`)
    REFERENCES    `gfg_catalog_products` (`id_product`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `categorie`
    FOREIGN KEY (`id_categorie`)
    REFERENCES    `gfg_categories` (`id_categorie`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS    `gfg_sizes` (
  `id_size` INT NOT NULL,
  `size` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_size`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS    `gfg_catalog_product_sizes` (
  `id_prod_size` INT NOT NULL AUTO_INCREMENT,
  `id_size` INT NOT NULL,
  `id_product` INT NOT NULL,
  PRIMARY KEY (`id_prod_size`),
  INDEX `produto_idx` (`id_product` ASC),
  INDEX `size_idx` (`id_size` ASC),
  CONSTRAINT `product`
    FOREIGN KEY (`id_product`)
    REFERENCES    `gfg_catalog_products` (`id_product`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `size`
    FOREIGN KEY (`id_size`)
    REFERENCES    `gfg_sizes` (`id_size`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS    `gfg_warehouse` (
  `id_warehouse` INT NOT NULL AUTO_INCREMENT,
  `warehouse` VARCHAR(250) NOT NULL,
  PRIMARY KEY (`id_warehouse`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS    `gfg_warehouse_control` (
  `id_wc` INT NOT NULL AUTO_INCREMENT,
  `id_product` INT NOT NULL,
  `id_size` INT NOT NULL,
  `id_warehouse` INT NOT NULL,
  `quantity` INT NULL,
  PRIMARY KEY (`id_wc`),
  INDEX `product_idx` (`id_product` ASC),
  INDEX `size_idx` (`id_size` ASC),
  INDEX `warehouse_idx` (`id_warehouse` ASC),
  CONSTRAINT `product`
    FOREIGN KEY (`id_product`)
    REFERENCES    `gfg_catalog_products` (`id_product`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `size`
    FOREIGN KEY (`id_size`)
    REFERENCES    `gfg_sizes` (`id_size`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `warehouse`
    FOREIGN KEY (`id_warehouse`)
    REFERENCES    `gfg_warehouse` (`id_warehouse`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS    `gfg_wms_call` (
  `id_wms_call` INT NOT NULL AUTO_INCREMENT,
  `time` DATETIME NOT NULL,
  `user` INT NOT NULL,
  PRIMARY KEY (`id_wms_call`))
ENGINE = InnoDB;
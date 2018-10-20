<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HousesInfo
 *
 * @ORM\Table(name="houses_info", indexes={@ORM\Index(name="show_built_year", columns={"show_built_year"}), @ORM\Index(name="complex_building_type", columns={"complex_building_type"}), @ORM\Index(name="aid", columns={"aid"})})
 * @ORM\Entity
 */
class HousesInfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="show_built_year", type="date", length=10, nullable=false)
     */
    private $show_built_year;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="take_land_time", type="date", length=10, nullable=false)
     */
    private $take_land_time;

    /**
     * @var string
     *
     * @ORM\Column(name="property_year", type="string", length=100, nullable=false)
     */
    private $property_year;

    /**
     * @var string
     *
     * @ORM\Column(name="complex_building_type", type="string", length=100, nullable=false)
     */
    private $complex_building_type;

    /**
     * @var string
     *
     * @ORM\Column(name="architectural", type="string", length=100, nullable=false)
     */
    private $architectural;

    /**
     * @var string
     *
     * @ORM\Column(name="decorate_standard", type="string", length=100, nullable=false)
     */
    private $decorate_standard;

    /**
     * @var integer
     *
     * @ORM\Column(name="entrance_mode", type="integer", length=100, nullable=false)
     */
    private $entrance_mode;

    /**
     * @var integer
     *
     * @ORM\Column(name="door_num", type="integer", length=10, nullable=false)
     */
    private $door_num;

    /**
     * @var string
     *
     * @ORM\Column(name="door_toward", type="string", length=100, nullable=false)
     */
    private $door_toward;

    /**
     * @var string
     *
     * @ORM\Column(name="door_depth", type="string", length=100, nullable=false)
     */
    private $door_depth;

    /**
     * @var string
     *
     * @ORM\Column(name="parking_rate", type="string", length=100, nullable=false)
     */
    private $parking_rate;

    /**
     * @var string
     *
     * @ORM\Column(name="parking_count", type="string", length=100, nullable=false)
     */
    private $parking_count;

    /**
     * @var integer
     *
     * @ORM\Column(name="mancar_shunt", type="integer", length=10, nullable=false)
     */
    private $mancar_shunt;

    /**
     * @var string
     *
     * @ORM\Column(name="parking_attr", type="string", length=100, nullable=false)
     */
    private $parking_attr;

    /**
     * @var string
     *
     * @ORM\Column(name="upper_parking_rentprice", type="string", length=100, nullable=false)
     */
    private $upper_parking_rentprice;

    /**
     * @var string
     *
     * @ORM\Column(name="upper_parking_saleprice", type="string", length=100, nullable=false)
     */
    private $upper_parking_saleprice;

    /**
     * @var string
     *
     * @ORM\Column(name="under_parking_rentprice", type="string", length=100, nullable=false)
     */
    private $under_parking_rentprice;

    /**
     * @var string
     *
     * @ORM\Column(name="under_parking_saleprice", type="string", length=100, nullable=false)
     */
    private $under_parking_saleprice;

    /**
     * @var string
     *
     * @ORM\Column(name="provide_water", type="string", length=100, nullable=false)
     */
    private $provide_water;

    /**
     * @var string
     *
     * @ORM\Column(name="provide_heating", type="string", length=100, nullable=false)
     */
    private $provide_heating;

    /**
     * @var string
     *
     * @ORM\Column(name="provide_electric", type="string", length=100, nullable=false)
     */
    private $provide_electric;

    /**
     * @var string
     *
     * @ORM\Column(name="provide_gas", type="string", length=100, nullable=false)
     */
    private $provide_gas;

    /**
     * @var string
     *
     * @ORM\Column(name="provide_network", type="string", length=100, nullable=false)
     */
    private $provide_network;

    /**
     * @var string
     *
     * @ORM\Column(name="provide_elevator", type="string", length=100, nullable=false)
     */
    private $provide_elevator;

    /**
     * @var string
     *
     * @ORM\Column(name="security", type="string", length=100, nullable=false)
     */
    private $security;

    /**
     * @var string
     *
     * @ORM\Column(name="hygiene", type="string", length=100, nullable=false)
     */
    private $hygiene;

    /**
     * @var string
     *
     * @ORM\Column(name="cleaner_personnel_total", type="string", length=100, nullable=false)
     */
    private $cleaner_personnel_total;

    /**
     * @var string
     *
     * @ORM\Column(name="green_personnel_total", type="string", length=100, nullable=false)
     */
    private $green_personnel_total;

    /**
     * @var string
     *
     * @ORM\Column(name="security_personnel_total", type="string", length=100, nullable=false)
     */
    private $security_personnel_total;

    /**
     * @var string
     *
     * @ORM\Column(name="property_personnel_total", type="string", length=100, nullable=false)
     */
    private $property_personnel_total;

    /**
     * @var string
     *
     * @ORM\Column(name="repair_personnel_total", type="string", length=100, nullable=false)
     */
    private $repair_personnel_total;

    /**
     * @var string
     *
     * @ORM\Column(name="repair_response_speed", type="string", length=100, nullable=false)
     */
    private $repair_response_speed;

    /**
     * @var string
     *
     * @ORM\Column(name="complex_tidy", type="string", length=100, nullable=false)
     */
    private $complex_tidy;

    /**
     * @var string
     *
     * @ORM\Column(name="complex_facilities", type="string", length=100, nullable=false)
     */
    private $complex_facilities;

    /**
     * @var string
     *
     * @ORM\Column(name="open_information", type="string", length=250, nullable=false)
     */
    private $open_information;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="firstnew_delivertime", type="date", length=10, nullable=false)
     */
    private $firstnew_delivertime;

    /**
     * @var string
     *
     * @ORM\Column(name="developer_offer", type="string", length=100, nullable=false)
     */
    private $developer_offer;

    /**
     * @var string
     *
     * @ORM\Column(name="evaluating", type="string", length=200, nullable=false)
     */
    private $evaluating;

    /**
     * @var integer
     *
     * @ORM\Column(name="complex_score", type="integer", length=10, nullable=false)
     */
    private $complex_score;

    /**
     * @var integer
     *
     * @ORM\Column(name="aid", type="integer", length=10, nullable=false)
     */
    private $aid;

    /**
     * @var integer
     *
     * @ORM\Column(name="complex_id", type="integer", length=10, nullable=false)
     */
    private $complex_id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", nullable=false)
     */
    private $checked;

    /**
     * @var string
     *
     * @ORM\Column(name="attributes", type="string", length=10, nullable=false)
     */
    private $attributes;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", length=5, nullable=false)
     */
    private $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(name="issystem", type="boolean", nullable=false)
     */
    private $issystem;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=100, nullable=false)
     */
    private $identifier;

    /**
     * @var integer
     *
     * @ORM\Column(name="create_time", type="integer", length=10, nullable=false)
     */
    private $create_time;

    /**
     * @var integer
     *
     * @ORM\Column(name="update_time", type="integer", length=10, nullable=false)
     */
    private $update_time;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_delete", type="boolean", length=1, nullable=false)
     */
    private $is_delete;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set show_built_year
     *
     * @param \DateTime $showBuiltYear
     * @return HousesInfo
     */
    public function setShowBuiltYear($showBuiltYear)
    {
        $this->show_built_year = $showBuiltYear;

        return $this;
    }

    /**
     * Get show_built_year
     *
     * @return \DateTime 
     */
    public function getShowBuiltYear()
    {
        return $this->show_built_year;
    }

    /**
     * Set take_land_time
     *
     * @param \DateTime $takeLandTime
     * @return HousesInfo
     */
    public function setTakeLandTime($takeLandTime)
    {
        $this->take_land_time = $takeLandTime;

        return $this;
    }

    /**
     * Get take_land_time
     *
     * @return \DateTime 
     */
    public function getTakeLandTime()
    {
        return $this->take_land_time;
    }

    /**
     * Set property_year
     *
     * @param string $propertyYear
     * @return HousesInfo
     */
    public function setPropertyYear($propertyYear)
    {
        $this->property_year = $propertyYear;

        return $this;
    }

    /**
     * Get property_year
     *
     * @return string 
     */
    public function getPropertyYear()
    {
        return $this->property_year;
    }

    /**
     * Set complex_building_type
     *
     * @param string $complexBuildingType
     * @return HousesInfo
     */
    public function setComplexBuildingType($complexBuildingType)
    {
        $this->complex_building_type = $complexBuildingType;

        return $this;
    }

    /**
     * Get complex_building_type
     *
     * @return string 
     */
    public function getComplexBuildingType()
    {
        return $this->complex_building_type;
    }

    /**
     * Set architectural
     *
     * @param string $architectural
     * @return HousesInfo
     */
    public function setArchitectural($architectural)
    {
        $this->architectural = $architectural;

        return $this;
    }

    /**
     * Get architectural
     *
     * @return string 
     */
    public function getArchitectural()
    {
        return $this->architectural;
    }

    /**
     * Set decorate_standard
     *
     * @param string $decorateStandard
     * @return HousesInfo
     */
    public function setDecorateStandard($decorateStandard)
    {
        $this->decorate_standard = $decorateStandard;

        return $this;
    }

    /**
     * Get decorate_standard
     *
     * @return string 
     */
    public function getDecorateStandard()
    {
        return $this->decorate_standard;
    }

    /**
     * Set entrance_mode
     *
     * @param integer $entranceMode
     * @return HousesInfo
     */
    public function setEntranceMode($entranceMode)
    {
        $this->entrance_mode = $entranceMode;

        return $this;
    }

    /**
     * Get entrance_mode
     *
     * @return integer 
     */
    public function getEntranceMode()
    {
        return $this->entrance_mode;
    }

    /**
     * Set door_num
     *
     * @param integer $doorNum
     * @return HousesInfo
     */
    public function setDoorNum($doorNum)
    {
        $this->door_num = $doorNum;

        return $this;
    }

    /**
     * Get door_num
     *
     * @return integer 
     */
    public function getDoorNum()
    {
        return $this->door_num;
    }

    /**
     * Set door_toward
     *
     * @param string $doorToward
     * @return HousesInfo
     */
    public function setDoorToward($doorToward)
    {
        $this->door_toward = $doorToward;

        return $this;
    }

    /**
     * Get door_toward
     *
     * @return string 
     */
    public function getDoorToward()
    {
        return $this->door_toward;
    }

    /**
     * Set door_depth
     *
     * @param string $doorDepth
     * @return HousesInfo
     */
    public function setDoorDepth($doorDepth)
    {
        $this->door_depth = $doorDepth;

        return $this;
    }

    /**
     * Get door_depth
     *
     * @return string 
     */
    public function getDoorDepth()
    {
        return $this->door_depth;
    }

    /**
     * Set parking_rate
     *
     * @param string $parkingRate
     * @return HousesInfo
     */
    public function setParkingRate($parkingRate)
    {
        $this->parking_rate = $parkingRate;

        return $this;
    }

    /**
     * Get parking_rate
     *
     * @return string 
     */
    public function getParkingRate()
    {
        return $this->parking_rate;
    }

    /**
     * Set parking_count
     *
     * @param string $parkingCount
     * @return HousesInfo
     */
    public function setParkingCount($parkingCount)
    {
        $this->parking_count = $parkingCount;

        return $this;
    }

    /**
     * Get parking_count
     *
     * @return string 
     */
    public function getParkingCount()
    {
        return $this->parking_count;
    }

    /**
     * Set mancar_shunt
     *
     * @param integer $mancarShunt
     * @return HousesInfo
     */
    public function setMancarShunt($mancarShunt)
    {
        $this->mancar_shunt = $mancarShunt;

        return $this;
    }

    /**
     * Get mancar_shunt
     *
     * @return integer 
     */
    public function getMancarShunt()
    {
        return $this->mancar_shunt;
    }

    /**
     * Set parking_attr
     *
     * @param string $parkingAttr
     * @return HousesInfo
     */
    public function setParkingAttr($parkingAttr)
    {
        $this->parking_attr = $parkingAttr;

        return $this;
    }

    /**
     * Get parking_attr
     *
     * @return string 
     */
    public function getParkingAttr()
    {
        return $this->parking_attr;
    }

    /**
     * Set upper_parking_rentprice
     *
     * @param string $upperParkingRentprice
     * @return HousesInfo
     */
    public function setUpperParkingRentprice($upperParkingRentprice)
    {
        $this->upper_parking_rentprice = $upperParkingRentprice;

        return $this;
    }

    /**
     * Get upper_parking_rentprice
     *
     * @return string 
     */
    public function getUpperParkingRentprice()
    {
        return $this->upper_parking_rentprice;
    }

    /**
     * Set upper_parking_saleprice
     *
     * @param string $upperParkingSaleprice
     * @return HousesInfo
     */
    public function setUpperParkingSaleprice($upperParkingSaleprice)
    {
        $this->upper_parking_saleprice = $upperParkingSaleprice;

        return $this;
    }

    /**
     * Get upper_parking_saleprice
     *
     * @return string 
     */
    public function getUpperParkingSaleprice()
    {
        return $this->upper_parking_saleprice;
    }

    /**
     * Set under_parking_rentprice
     *
     * @param string $underParkingRentprice
     * @return HousesInfo
     */
    public function setUnderParkingRentprice($underParkingRentprice)
    {
        $this->under_parking_rentprice = $underParkingRentprice;

        return $this;
    }

    /**
     * Get under_parking_rentprice
     *
     * @return string 
     */
    public function getUnderParkingRentprice()
    {
        return $this->under_parking_rentprice;
    }

    /**
     * Set under_parking_saleprice
     *
     * @param string $underParkingSaleprice
     * @return HousesInfo
     */
    public function setUnderParkingSaleprice($underParkingSaleprice)
    {
        $this->under_parking_saleprice = $underParkingSaleprice;

        return $this;
    }

    /**
     * Get under_parking_saleprice
     *
     * @return string 
     */
    public function getUnderParkingSaleprice()
    {
        return $this->under_parking_saleprice;
    }

    /**
     * Set provide_water
     *
     * @param string $provideWater
     * @return HousesInfo
     */
    public function setProvideWater($provideWater)
    {
        $this->provide_water = $provideWater;

        return $this;
    }

    /**
     * Get provide_water
     *
     * @return string 
     */
    public function getProvideWater()
    {
        return $this->provide_water;
    }

    /**
     * Set provide_heating
     *
     * @param string $provideHeating
     * @return HousesInfo
     */
    public function setProvideHeating($provideHeating)
    {
        $this->provide_heating = $provideHeating;

        return $this;
    }

    /**
     * Get provide_heating
     *
     * @return string 
     */
    public function getProvideHeating()
    {
        return $this->provide_heating;
    }

    /**
     * Set provide_electric
     *
     * @param string $provideElectric
     * @return HousesInfo
     */
    public function setProvideElectric($provideElectric)
    {
        $this->provide_electric = $provideElectric;

        return $this;
    }

    /**
     * Get provide_electric
     *
     * @return string 
     */
    public function getProvideElectric()
    {
        return $this->provide_electric;
    }

    /**
     * Set provide_gas
     *
     * @param string $provideGas
     * @return HousesInfo
     */
    public function setProvideGas($provideGas)
    {
        $this->provide_gas = $provideGas;

        return $this;
    }

    /**
     * Get provide_gas
     *
     * @return string 
     */
    public function getProvideGas()
    {
        return $this->provide_gas;
    }

    /**
     * Set provide_network
     *
     * @param string $provideNetwork
     * @return HousesInfo
     */
    public function setProvideNetwork($provideNetwork)
    {
        $this->provide_network = $provideNetwork;

        return $this;
    }

    /**
     * Get provide_network
     *
     * @return string 
     */
    public function getProvideNetwork()
    {
        return $this->provide_network;
    }

    /**
     * Set provide_elevator
     *
     * @param string $provideElevator
     * @return HousesInfo
     */
    public function setProvideElevator($provideElevator)
    {
        $this->provide_elevator = $provideElevator;

        return $this;
    }

    /**
     * Get provide_elevator
     *
     * @return string 
     */
    public function getProvideElevator()
    {
        return $this->provide_elevator;
    }

    /**
     * Set security
     *
     * @param string $security
     * @return HousesInfo
     */
    public function setSecurity($security)
    {
        $this->security = $security;

        return $this;
    }

    /**
     * Get security
     *
     * @return string 
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * Set hygiene
     *
     * @param string $hygiene
     * @return HousesInfo
     */
    public function setHygiene($hygiene)
    {
        $this->hygiene = $hygiene;

        return $this;
    }

    /**
     * Get hygiene
     *
     * @return string 
     */
    public function getHygiene()
    {
        return $this->hygiene;
    }

    /**
     * Set cleaner_personnel_total
     *
     * @param string $cleanerPersonnelTotal
     * @return HousesInfo
     */
    public function setCleanerPersonnelTotal($cleanerPersonnelTotal)
    {
        $this->cleaner_personnel_total = $cleanerPersonnelTotal;

        return $this;
    }

    /**
     * Get cleaner_personnel_total
     *
     * @return string 
     */
    public function getCleanerPersonnelTotal()
    {
        return $this->cleaner_personnel_total;
    }

    /**
     * Set green_personnel_total
     *
     * @param string $greenPersonnelTotal
     * @return HousesInfo
     */
    public function setGreenPersonnelTotal($greenPersonnelTotal)
    {
        $this->green_personnel_total = $greenPersonnelTotal;

        return $this;
    }

    /**
     * Get green_personnel_total
     *
     * @return string 
     */
    public function getGreenPersonnelTotal()
    {
        return $this->green_personnel_total;
    }

    /**
     * Set security_personnel_total
     *
     * @param string $securityPersonnelTotal
     * @return HousesInfo
     */
    public function setSecurityPersonnelTotal($securityPersonnelTotal)
    {
        $this->security_personnel_total = $securityPersonnelTotal;

        return $this;
    }

    /**
     * Get security_personnel_total
     *
     * @return string 
     */
    public function getSecurityPersonnelTotal()
    {
        return $this->security_personnel_total;
    }

    /**
     * Set property_personnel_total
     *
     * @param string $propertyPersonnelTotal
     * @return HousesInfo
     */
    public function setPropertyPersonnelTotal($propertyPersonnelTotal)
    {
        $this->property_personnel_total = $propertyPersonnelTotal;

        return $this;
    }

    /**
     * Get property_personnel_total
     *
     * @return string 
     */
    public function getPropertyPersonnelTotal()
    {
        return $this->property_personnel_total;
    }

    /**
     * Set repair_personnel_total
     *
     * @param string $repairPersonnelTotal
     * @return HousesInfo
     */
    public function setRepairPersonnelTotal($repairPersonnelTotal)
    {
        $this->repair_personnel_total = $repairPersonnelTotal;

        return $this;
    }

    /**
     * Get repair_personnel_total
     *
     * @return string 
     */
    public function getRepairPersonnelTotal()
    {
        return $this->repair_personnel_total;
    }

    /**
     * Set repair_response_speed
     *
     * @param string $repairResponseSpeed
     * @return HousesInfo
     */
    public function setRepairResponseSpeed($repairResponseSpeed)
    {
        $this->repair_response_speed = $repairResponseSpeed;

        return $this;
    }

    /**
     * Get repair_response_speed
     *
     * @return string 
     */
    public function getRepairResponseSpeed()
    {
        return $this->repair_response_speed;
    }

    /**
     * Set complex_tidy
     *
     * @param string $complexTidy
     * @return HousesInfo
     */
    public function setComplexTidy($complexTidy)
    {
        $this->complex_tidy = $complexTidy;

        return $this;
    }

    /**
     * Get complex_tidy
     *
     * @return string 
     */
    public function getComplexTidy()
    {
        return $this->complex_tidy;
    }

    /**
     * Set complex_facilities
     *
     * @param string $complexFacilities
     * @return HousesInfo
     */
    public function setComplexFacilities($complexFacilities)
    {
        $this->complex_facilities = $complexFacilities;

        return $this;
    }

    /**
     * Get complex_facilities
     *
     * @return string 
     */
    public function getComplexFacilities()
    {
        return $this->complex_facilities;
    }

    /**
     * Set open_information
     *
     * @param string $openInformation
     * @return HousesInfo
     */
    public function setOpenInformation($openInformation)
    {
        $this->open_information = $openInformation;

        return $this;
    }

    /**
     * Get open_information
     *
     * @return string 
     */
    public function getOpenInformation()
    {
        return $this->open_information;
    }

    /**
     * Set firstnew_delivertime
     *
     * @param \DateTime $firstnewDelivertime
     * @return HousesInfo
     */
    public function setFirstnewDelivertime($firstnewDelivertime)
    {
        $this->firstnew_delivertime = $firstnewDelivertime;

        return $this;
    }

    /**
     * Get firstnew_delivertime
     *
     * @return \DateTime 
     */
    public function getFirstnewDelivertime()
    {
        return $this->firstnew_delivertime;
    }

    /**
     * Set developer_offer
     *
     * @param string $developerOffer
     * @return HousesInfo
     */
    public function setDeveloperOffer($developerOffer)
    {
        $this->developer_offer = $developerOffer;

        return $this;
    }

    /**
     * Get developer_offer
     *
     * @return string 
     */
    public function getDeveloperOffer()
    {
        return $this->developer_offer;
    }

    /**
     * Set evaluating
     *
     * @param string $evaluating
     * @return HousesInfo
     */
    public function setEvaluating($evaluating)
    {
        $this->evaluating = $evaluating;

        return $this;
    }

    /**
     * Get evaluating
     *
     * @return string 
     */
    public function getEvaluating()
    {
        return $this->evaluating;
    }

    /**
     * Set complex_score
     *
     * @param integer $complexScore
     * @return HousesInfo
     */
    public function setComplexScore($complexScore)
    {
        $this->complex_score = $complexScore;

        return $this;
    }

    /**
     * Get complex_score
     *
     * @return integer 
     */
    public function getComplexScore()
    {
        return $this->complex_score;
    }

    /**
     * Set aid
     *
     * @param integer $aid
     * @return HousesInfo
     */
    public function setAid($aid)
    {
        $this->aid = $aid;

        return $this;
    }

    /**
     * Get aid
     *
     * @return integer 
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * Set complex_id
     *
     * @param integer $complexId
     * @return HousesInfo
     */
    public function setComplexId($complexId)
    {
        $this->complex_id = $complexId;

        return $this;
    }

    /**
     * Get complex_id
     *
     * @return integer 
     */
    public function getComplexId()
    {
        return $this->complex_id;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return HousesInfo
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked
     *
     * @return boolean 
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return HousesInfo
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get attributes
     *
     * @return string 
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return HousesInfo
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set issystem
     *
     * @param boolean $issystem
     * @return HousesInfo
     */
    public function setIssystem($issystem)
    {
        $this->issystem = $issystem;

        return $this;
    }

    /**
     * Get issystem
     *
     * @return boolean 
     */
    public function getIssystem()
    {
        return $this->issystem;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return HousesInfo
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set create_time
     *
     * @param integer $createTime
     * @return HousesInfo
     */
    public function setCreateTime($createTime)
    {
        $this->create_time = $createTime;

        return $this;
    }

    /**
     * Get create_time
     *
     * @return integer 
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * Set update_time
     *
     * @param integer $updateTime
     * @return HousesInfo
     */
    public function setUpdateTime($updateTime)
    {
        $this->update_time = $updateTime;

        return $this;
    }

    /**
     * Get update_time
     *
     * @return integer 
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    /**
     * Set is_delete
     *
     * @param boolean $isDelete
     * @return HousesInfo
     */
    public function setIsDelete($isDelete)
    {
        $this->is_delete = $isDelete;

        return $this;
    }

    /**
     * Get is_delete
     *
     * @return boolean 
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }
}

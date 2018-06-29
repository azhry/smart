<?php 
/**
 * A class implementing Simple Multi Attribute Rating Technique (SMART) method.
 *
 * @author Azhary Arliansyah <arliansyah_azhary@yahoo.com>
 */


class Smart
{
	private $config;
	private $criteria;
	private $predicates;
	private $weights;
	private $data;

	public function __construct()
	{
		$this->config = json_decode(file_get_contents(realpath(__DIR__ . '/config.json')), true);
		$this->criteria = $this->config['criteria'];
		$this->predicates = $this->config['predicates'];
		$this->weights = array_column($this->criteria, 'weight');
	}

	public function fit($data)
	{
		$this->data = $data;
	}

	public function result()
	{
		$result = 0;
		foreach ($this->criteria as $criterion)
		{
			$value = $this->data[$criterion['name']];
			$normalizedWeight = $this->normalize($criterion['weight']);

			if ($criterion['type'] == 'range')
			{
				foreach ($criterion['rules'] as $rule)
				{
					if (isset($rule['max'], $rule['min']))
					{
						if ($value >= $rule['min'] && $value <= $rule['max'])
						{
							$result += $rule['value'] * $normalizedWeight;
						}
					}
					elseif (!isset($rule['max']))
					{
						if ($value >= $rule['min'])
						{
							$result += $rule['value'] * $normalizedWeight;
						}
					}
					elseif (!isset($rule['min']))
					{
						if ($value <= $rule['max'])
						{
							$result += $rule['value'] * $normalizedWeight;
						}
					}
				}
			}
			elseif ($criterion['type'] == 'pair')
			{
				$result += $criterion['rules'][$value] * $normalizedWeight;
			}
		}

		return $result;
	}

	public function predicate()
	{
		$result = $this->result();
		foreach ($this->predicates as $predicate)
		{
			if ($result >= $predicate['min'] && $result <= $predicate['max'])
			{
				return $predicate['label'];
			}
		}
		return false;
	}

	private function normalize($criterion)
	{
		return $criterion / array_sum($this->weights);
	}
}
<?php

/**
 * 二叉搜索树，也叫做二叉查找树，二叉排序树
 * 定义：空树或者满足左节点小于等于根节点，右节点大于等于根节点，子树均为二叉搜索树的条件的二叉树
 * 1.给定数组，构造BST
 * 2.三种遍历的实现
 * 3.证明某树是BST
 * 4.给定一个数，搜索是否存在该数
 * @package default
 * @author  ccyours
 * @date:   2017/10/24
 * @time:   16:07
 */
class BinarySearchTreeNode {
	private $_data = 0;
	private $_left = null;
	private $_right = null;
	private $_parent = null;

	public function __construct($data) {
		$this->_data = $data;
	}

	public function setData($data) {
		$this->_data = $data;
	}

	public function getData() {
		return $this->_data;
	}

	public function setLeft($node) {
		$this->_left = $node;
	}

	public function getLeft() {
		return $this->_left;
	}

	public function setRight($node) {
		$this->_right = $node;
	}

	public function getRight() {
		return $this->_right;
	}

	public function setParent($node) {
		$this->_parent = $node;
	}

	public function getParent() {
		return $this->_parent;
	}
}

/**
 * Class BinarySearchTree
 * 二叉树的结构定义，也可以直接写方法。无需定义结构
 */
class BinarySearchTree {
	//定义根节点
	private $_root = null;

	public function __construct(&$node) {
		$this->_root = $node;
	}

	public function getRoot() {
		return $this->_root;
	}

	/**
	 * 给定一个值，将其插入到已有的BST中。思路如下：
	 * 1.生成一个节点
	 * 2.查找节点位置
	 * 3.插入该结点
	 */
	public function insert($value) {
		//定义节点
		$insertNode = new BinarySearchTreeNode($value);
		//查找节点位置
		if ($this->_root == null) {
			//说明是空树
			$this->_root = $insertNode;
		} else {
			$addressNode = $this->_root;//记录当前节点的地址
			$tmpNode = $addressNode;//记录当前节点的数据
			//由于每次插入均遵守BST原则，因此新插入的节点肯定在叶子上。
			while ($addressNode != null) {
				$tmpNode = $addressNode;
				if ($insertNode->getData() == $addressNode->getData()) {
					echo 'node repeat';
					return;
				} elseif ($insertNode->getData() > $addressNode->getData()) {
					$addressNode = $addressNode->getRight();
				} else {
					$addressNode = $addressNode->getLeft();
				}
			}
			//此时$addressNode为空，因此需要比较tmpNode和新节点的值大小
			if ($insertNode->getData() == $tmpNode->getData()) {
				echo 'node repeat';
				return;
			} elseif ($insertNode->getData() > $tmpNode->getData()) {
				$tmpNode->setRight($insertNode);
			} else {
				$tmpNode->setLeft($insertNode);
			}
			//绑定父亲节点
			$insertNode->setParent($tmpNode);
			return;
		}
	}

	/**
	 * 先序遍历
	 * 先根后左最后右
	 * @param $node 当前树的根节点
	 */
	public function leftView($node) {
		if ($node != null) {
			echo '</br>';
			echo $node->getData();//遍历根节点
			$this->leftView($node->getLeft());//遍历左节点
			$this->leftView($node->getRight());//遍历右节点
		}
	}

	/**
	 * 中序遍历
	 * 先左后根最后右
	 * @param $node 当前树的根节点
	 */
	public function midView($node) {
		if ($node != null) {
			$this->midView($node->getLeft());//遍历左节点
			echo '</br>';
			echo $node->getData();//遍历根节点
			$this->midView($node->getRight());//遍历右节点
		}
	}

	/**
	 * 后序遍历
	 * 先左后右最后根
	 * @param $node 当前树的根节点
	 */
	public function lastView($node) {
		if ($node != null) {
			$this->lastView($node->getLeft());//遍历左节点
			$this->lastView($node->getRight());//遍历右节点
			echo '</br>';
			echo $node->getData();//遍历根节点
		}
	}

	/**
	 * 证明二叉树是查找（搜索）树
	 * @param $node 当前树的根节点
	 */
	public function isBST($node) {
		if ($node == null) {
			return true;
		}
		while ($node->getLeft() != null) {
			if ($node->getLeft()->getData() >= $node->getData()) {
				return false;
			}
			$node = $node->getLeft();
		}
		while ($node->getLeft() != null) {
			if ($node->getRight()->getData() <= $node->getData()) {
				return false;
			}
			$node = $node->getRight();
		}
		return true;
	}

	/**
	 * @param $node 当前树的根节点
	 * @param $value 要搜索的值
	 */
	public function findValue($node, $value) {
		static $searchCount = 0;//记录当前搜索次数
		$searchCount++;
		if ($node == null) {
			echo "经过{$searchCount}次搜索，数据不存在";
			return;
		}
		if ($node->getData() == $value) {
			echo "经过{$searchCount}次搜索，数据存在";
			return;
		} elseif ($node->getData() > $value) {
			$this->findValue($node->getLeft(), $value);
		} else {
			$this->findValue($node->getRight(), $value);
		}
	}
}


//给定一个数组，构造二叉搜索树。数据：1,3,8,10,33,45,78,23,50,44
$data = [1, 8, 3, 33, 33, 21, 45, 78, 23, 50, 44];
$root = null;
$tree = new BinarySearchTree($root);
foreach ($data as $d) {
	$tree->insert($d);
}
echo '<hr>先序遍历';
$tree->leftView($tree->getRoot());
echo '<hr>中序遍历';
$tree->midView($tree->getRoot());
echo '<hr>后序遍历';
$tree->lastView($tree->getRoot());
echo '<hr>证明是否为BST：';
echo $tree->isBST($tree->getRoot()) ? '是' : '不是';
echo '<hr>搜索值是否存在：';
$tree->findValue($tree->getRoot(), 44);//44如果按照数组中顺序查找，需要11次。现在只需要5次